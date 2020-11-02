<?php

declare(strict_types=1);

namespace Milo\Github\Storages;

use Milo\Github;


/**
 * Naive file cache implementation.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class FileCache extends Github\Sanity implements ICache
{
	/** @var string */
	private $dir;


	/**
	 * @throws MissingDirectoryException
	 */
	public function __construct(string $tempDir)
	{
		if (!is_dir($tempDir)) {
			throw new MissingDirectoryException("Directory '$tempDir' is missing.");
		}

		$dir = $tempDir . DIRECTORY_SEPARATOR . 'milo.github-api';

		if (!is_dir($dir)) {
			set_error_handler(function($severity, $message, $file, $line) use ($dir, &$valid) {
				restore_error_handler();
				if (!is_dir($dir)) {
					throw new MissingDirectoryException("Cannot create '$dir' directory.", 0, new \ErrorException($message, 0, $severity, $file, $line));
				}
			});
			mkdir($dir);
			restore_error_handler();
		}

		$this->dir = $dir;
	}


	/**
	 * @param  mixed $value
	 * @return mixed  stored value
	 */
	public function save(string $key, $value)
	{
		file_put_contents(
			$this->filePath($key),
			serialize($value),
			LOCK_EX
		);

		return $value;
	}


	/**
	 * @param  string $key
	 * @return mixed|null
	 */
	public function load(string $key)
	{
		$path = $this->filePath($key);
		if (is_file($path) && ($fd = fopen($path, 'rb')) && flock($fd, LOCK_SH)) {
			$cached = stream_get_contents($fd);
			flock($fd, LOCK_UN);
			fclose($fd);

			$success = true;
			set_error_handler(function() use (&$success) { return $success = false; }, E_NOTICE);
			$cached = unserialize($cached);
			restore_error_handler();

			if ($success) {
				return $cached;
			}
		}

		return null;
	}


	private function filePath(string $key): string
	{
		return $this->dir . DIRECTORY_SEPARATOR . sha1($key) . '.php';
	}
}
