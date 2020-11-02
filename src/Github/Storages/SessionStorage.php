<?php

declare(strict_types=1);

namespace Milo\Github\Storages;

use Milo\Github;


/**
 * Session storage which uses $_SESSION directly. Session must be started already before use.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class SessionStorage extends Github\Sanity implements ISessionStorage
{
	const SESSION_KEY = 'milo.github-api';

	/** @var string */
	private $sessionKey;


	public function __construct(string $sessionKey = self::SESSION_KEY)
	{
		$this->sessionKey = $sessionKey;
	}


	/**
	 * @param  mixed $value
	 * @return static
	 */
	public function set(string $name, $value)
	{
		if ($value === null) {
			return $this->remove($name);
		}

		$this->check(__METHOD__);
		$_SESSION[$this->sessionKey][$name] = $value;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function get(string $name)
	{
		$this->check(__METHOD__);

		return isset($_SESSION[$this->sessionKey][$name])
			? $_SESSION[$this->sessionKey][$name]
			: null;
	}


	/**
	 * @return static
	 */
	public function remove(string $name)
	{
		$this->check(__METHOD__);

		unset($_SESSION[$this->sessionKey][$name]);

		return $this;
	}


	/**
	 * @param  string
	 */
	private function check(string $method)
	{
		if (!isset($_SESSION)) {
			trigger_error("Start session before using $method().", E_USER_WARNING);
		}
	}
}
