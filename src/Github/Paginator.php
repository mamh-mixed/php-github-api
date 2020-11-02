<?php

declare(strict_types=1);

namespace Milo\Github;


/**
 * Iterates through the Github API responses by Link: header.
 *
 * @see https://developer.github.com/guides/traversing-with-pagination/
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class Paginator extends Sanity implements \Iterator
{
	/** @var Api */
	private $api;

	/** @var Http\Request */
	private $firstRequest;

	/** @var Http\Request|null */
	private $request;

	/** @var Http\Response|null */
	private $response;

	/** @var int */
	private $limit;

	/** @var int */
	private $counter = 0;


	public function __construct(Api $api, Http\Request $request)
	{
		$this->api = $api;
		$this->firstRequest = clone $request;
	}


	/**
	 * Limits maximum steps of iteration.
	 */
	public function limit(?int $limit): self
	{
		$this->limit = $limit === null
			? null
			: (int) $limit;

		return $this;
	}


	public function rewind(): void
	{
		$this->request = $this->firstRequest;
		$this->response = null;
		$this->counter = 0;
	}


	public function valid(): bool
	{
		return $this->request !== null && ($this->limit === null || $this->counter < $this->limit);
	}


	public function current(): Http\Response
	{
		$this->load();
		return $this->response;
	}


	public function key(): int
	{
		return static::parsePage($this->request->getUrl());
	}


	public function next(): void
	{
		$this->load();

		if ($url = static::parseLink($this->response->getHeader('Link'), 'next')) {
			$this->request = new Http\Request(
				$this->request->getMethod(),
				$url,
				$this->request->getHeaders(),
				$this->request->getContent()
			);
		} else {
			$this->request = null;
		}

		$this->response = null;
		$this->counter++;
	}


	private function load()
	{
		if ($this->response === null) {
			$this->response = $this->api->request($this->request);
		}
	}


	public static function parsePage(string $url): int
	{
		list (, $parametersStr) = explode('?', $url, 2) + ['', ''];
		parse_str($parametersStr, $parameters);

		return isset($parameters['page'])
			? max(1, (int) $parameters['page'])
			: 1;
	}


	/**
	 * @see  https://developer.github.com/guides/traversing-with-pagination/#navigating-through-the-pages
	 */
	public static function parseLink(?string $link, ?string $rel): ?string
	{
		if (!preg_match('(<([^>]+)>;\s*rel="' . preg_quote("$rel") . '")', "$link", $match)) {
			return null;
		}

		return $match[1];
	}
}
