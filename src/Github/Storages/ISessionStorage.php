<?php

declare(strict_types=1);

namespace Milo\Github\Storages;


/**
 * Cross-request session storage.
 */
interface ISessionStorage
{
	/**
	 * @param  mixed $value
	 * @return static
	 */
	function set(string $name, $value);


	/**
	 * @return mixed
	 */
	function get(string $name);


	/**
	 * @return static
	 */
	function remove(string $name);
}
