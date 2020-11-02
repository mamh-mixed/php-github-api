<?php

declare(strict_types=1);

namespace Milo\Github\Http;

use Milo\Github;


/**
 * HTTP request or response ascendant.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
abstract class Message extends Github\Sanity
{
	/** @var array[name => value] */
	private $headers = [];

	/** @var string|null */
	private $content;


	public function __construct(array $headers = [], string $content = null)
	{
		$this->headers = array_change_key_case($headers, CASE_LOWER);
		$this->content = $content;
	}


	public function hasHeader(string $name): bool
	{
		return array_key_exists(strtolower($name), $this->headers);
	}


	public function getHeader(string $name, $default = null)
	{
		$name = strtolower($name);
		return array_key_exists($name, $this->headers)
			? $this->headers[$name]
			: $default;
	}


	/**
	 * @return static
	 */
	protected function addHeader(string $name, $value)
	{
		$name = strtolower($name);
		if (!array_key_exists($name, $this->headers) && $value !== null) {
			$this->headers[$name] = $value;
		}

		return $this;
	}


	/**
	 * @return static
	 */
	protected function setHeader(string $name, $value)
	{
		$name = strtolower($name);
		if ($value === null) {
			unset($this->headers[$name]);
		} else {
			$this->headers[$name] = $value;
		}

		return $this;
	}


	public function getHeaders(): array
	{
		return $this->headers;
	}


	public function getContent(): ?string
	{
		return $this->content;
	}
}
