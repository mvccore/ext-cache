<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Caches;

use \MvcCore\Ext\ICache;

class Base {

	/** @var array<string,\MvcCore\Ext\Caches\Base> */
	protected static $instances	= [];

	/** @var array */
	protected static $defaults	= [];

	/** @var \stdClass|NULL */
	protected $config			= NULL;
	
	/** @var bool|NULL */
	protected $installed		= NULL;
	
	/** @var mixed */
	protected $provider			= NULL;

	/** @var bool */
	protected $enabled			= FALSE;

	/** @var bool|NULL */
	protected $connected		= NULL;
	
	/** @var \MvcCore\Application */
	protected $application		= NULL;
	
	/**
	 * Create or get memory cached cache wrapper instance.
	 * @param string|array|NULL $connectionArguments,...
	 * @return \MvcCore\Ext\Caches\Base
	 */
	public static function GetInstance (/*...$connectionNameOrArguments = NULL*/) {
		$args = func_get_args();
		$nameKey = ICache::CONNECTION_NAME;
		$config = static::$defaults;
		$connectionName = $config[$nameKey];
		if (isset($args[0])) {
			$arg = & $args[0];
			if (is_string($arg)) {
				$connectionName = $arg;
			} else if (is_array($arg)) {
				$connectionName = isset($arg[$nameKey])
					? $arg[$nameKey]
					: static::$defaults[$nameKey];
				$config = $arg;
			} else if ($arg !== NULL) {
				throw new \InvalidArgumentException(
					"[".get_called_class()."] Cache instance getter argument could be ".
					"only a string connection name or connection config array."
				);
			}
		}
		if (!isset(self::$instances[$connectionName]))
			self::$instances[$connectionName] = new static($config);
		return self::$instances[$connectionName];
	}

	/**
	 * Create cache wrapper instance.
	 * @param array $config Connection config array.
	 */
	protected function __construct (array $config = []) {
		$hostKey	= ICache::CONNECTION_HOST;
		$portKey	= ICache::CONNECTION_PORT;
		$timeoutKey	= ICache::CONNECTION_TIMEOUT;
		$dbKey		= ICache::CONNECTION_DATABASE;
		if (!isset($config[$hostKey]))
			$config[$hostKey] = static::$defaults[$hostKey];
		if (!isset($config[$portKey]))
			$config[$portKey] = static::$defaults[$portKey];
		if (!isset($config[$timeoutKey])) 
			$config[$timeoutKey] = static::$defaults[$timeoutKey];
		if (!isset($config[$dbKey]))
			$config[$dbKey]	= static::$defaults[$dbKey];
		$this->config = (object) $config;
		$this->application = \MvcCore\Application::GetInstance();
	}
	
	/**
	 * Get provider instance.
	 * @return object|NULL
	 */
	public function GetProvider () {
		return $this->provider;
	}

	/**
	 * Set provider instance.
	 * @param  object|NULL $provider
	 * @return \MvcCore\Ext\Caches\Base
	 */
	public function SetProvider ($provider) {
		$this->provider = $provider;
		return $this;
	}
	
	/**
	 * Return initial configuration data.
	 * @return \stdClass
	 */
	public function GetConfig () {
		return $this->config;
	}

	/**
	 * Enable/disable cache wrapper.
	 * @param  bool $enable
	 * @return \MvcCore\Ext\Caches\Memcache
	 */
	public function SetEnabled ($enabled) {
		if ($enabled) {
			$enabled = ($this->installed && (
				$this->connected === NULL ||
				$this->connected === TRUE
			));
		}
		$this->enabled = $enabled;
		return $this;
	}

	/**
	 * Get if cache wrapper is enabled/disabled.
	 * @return bool
	 */
	public function GetEnabled () {
		return $this->enabled;
	}
	
	/**
	 * Handle exception localy.
	 * @param  \Exception|\Throwable $e
	 * @throws \Exception|\Throwable
	 * @return void
	 */
	protected function exceptionHandler ($e) {
		if ($this->application->GetEnvironment()->IsDevelopment()) {
			throw $e;
		} else {
			$debugClass = $this->application->GetDebugClass();
			$debugClass::Log($e);
		}
	}

}
