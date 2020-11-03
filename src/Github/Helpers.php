<?php

declare(strict_types=1);

namespace Milo\Github;


/**
 * Just helpers.
 *
 * The JSON encode/decode methods are stolen from Nette Utils (https://github.com/nette/utils).
 *
 * @author  David Grudl
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class Helpers
{
	/** @var Http\IClient */
	private static $client;


	/**
	 * @param  mixed $value
	 *
	 * @throws JsonException
	 */
	public static function jsonEncode($value): string
	{
		$json = json_encode($value, JSON_UNESCAPED_UNICODE);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}
		return $json;
	}


	/**
	 * @return mixed
	 *
	 * @throws JsonException
	 */
	public static function jsonDecode(string $json)
	{
		$json = (string) $json;
		if (!preg_match('##u', $json)) {
			throw new JsonException('Invalid UTF-8 sequence', 5); // PECL JSON-C
		}

		$value = json_decode($json, false, 512, (defined('JSON_C_VERSION') && PHP_INT_SIZE > 4) ? 0 : JSON_BIGINT_AS_STRING);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}
		return $value;
	}


	public static function createDefaultClient(bool $newInstance = false): Http\IClient
	{
		if (self::$client === null || $newInstance) {
			self::$client = extension_loaded('curl')
				? new Http\CurlClient
				: new Http\StreamClient;
		}

		return self::$client;
	}
}
