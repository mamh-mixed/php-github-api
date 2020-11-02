<?php

declare(strict_types=1);

namespace Milo\Github\Http;


/**
 * HTTP client interface.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
interface IClient
{
	function request(Request $request): Response;


	/**
	 * @param  callable|null function(Request $request)
	 * @return static
	 */
	function onRequest(?callable $callback);


	/**
	 * @param  callable|null function(Response $request)
	 * @return static
	 */
	function onResponse(?callable $callback);
}
