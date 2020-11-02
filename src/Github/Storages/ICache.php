<?php

declare(strict_types=1);

namespace Milo\Github\Storages;


interface ICache
{
	/**
	 * @param  mixed $value
	 * @return mixed  stored value
	 */
	function save(string $key, $value);


	/**
	 * @return mixed|null
	 */
	function load(string $key);
}
