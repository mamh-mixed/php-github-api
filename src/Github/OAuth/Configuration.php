<?php

declare(strict_types=1);

namespace Milo\Github\OAuth;

use Milo\Github;


/**
 * Configuration for OAuth token obtaining.
 *
 * @author  Miloslav Hůla (https://github.com/milo)
 */
class Configuration
{
	use Github\Strict;

	/** @var string */
	public $clientId;

	/** @var string */
	public $clientSecret;

	/** @var string[] */
	public $scopes;


	/**
	 * @param  string[] $scopes
	 */
	public function __construct(string $clientId, string $clientSecret, array $scopes = [])
	{
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->scopes = $scopes;
    }


	public static function fromArray(array $conf): Configuration
	{
		return new static($conf['clientId'], $conf['clientSecret'], isset($conf['scopes']) ? $conf['scopes'] : []);
	}
}
