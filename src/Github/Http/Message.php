<?php

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


	/**
	 * @param  array
	 * @param  string|null
	 */
	public function __construct(array $headers = [], $content = null)
	{
		$this->headers = array_change_key_case($headers, CASE_LOWER);
		$this->content = $content;
	}


	/**
	 * @param  string
	 * @return bool
	 */
	public function hasHeader($name)
	{
		return array_key_exists(strtolower($name), $this->headers);
	}


	/**
	 * @param  string
	 * @param  mixed
	 * @return mixed
	 */
	public function getHeader($name, $default = null)
	{
		$name = strtolower($name);
		return array_key_exists($name, $this->headers)
			? $this->headers[$name]
			: $default;
	}


	/**
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function addHeader($name, $value)
	{
		$name = strtolower($name);
		if (!array_key_exists($name, $this->headers) && $value !== null) {
			$this->headers[$name] = $value;
		}

		return $this;
	}


	/**
	 * @param  string
	 * @param  string|null
	 * @return self
	 */
	protected function setHeader($name, $value)
	{
		$name = strtolower($name);
		if ($value === null) {
			unset($this->headers[$name]);
		} else {
			$this->headers[$name] = $value;
		}

		return $this;
	}


	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}


	/**
	 * @return string|null
	 */
	public function getContent()
	{
		return $this->content;
	}
}
