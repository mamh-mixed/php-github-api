<?php

declare(strict_types=1);

namespace Milo\Github\Http;


/**
 * HTTP request envelope.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class Request extends Message
{
	/** HTTP request method */
	const
		DELETE = 'DELETE',
		GET = 'GET',
		HEAD = 'HEAD',
		PATCH = 'PATCH',
		POST = 'POST',
		PUT = 'PUT';


	/** @var string */
	private $method;

	/** @var string */
	private $url;


	public function __construct(string $method, string $url, array $headers = [], string $content = null)
	{
		$this->method = $method;
		$this->url = $url;
		parent::__construct($headers, $content);
	}


	public function isMethod(string $method): bool
	{
		return strcasecmp($this->method, $method) === 0;
	}


	public function getMethod(): string
	{
		return $this->method;
	}


	public function getUrl(): string
	{
		return $this->url;
	}


	/**
	 * @return static
	 */
	public function addHeader(string $name, $value)
	{
		return parent::addHeader($name, $value);
	}


	/**
	 * @return static
	 */
	public function setHeader(string $name, $value)
	{
		return parent::setHeader($name, $value);
	}
}
