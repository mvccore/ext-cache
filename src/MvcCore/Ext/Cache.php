<?php

namespace MvcCore\Ext;

class Cache
{
	/**
	 * MvcCore Extension - Form - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0-alpha';

	/**
	 * Keys are store names, values are instances of `\MvcCore\Ext\ICache`.
	 * @var array
	 */
	protected static $stores = [];

	/**
	 * Default store.
	 * @var \MvcCore\Ext\ICache|NULL
	 */
	protected static $default = NULL;

	/**
	 * Get store by name or get store registered as default.
	 * @param string|NULL $name
	 * @return \MvcCore\Ext\ICache|NULL
	 */
	public static function GetStore ($name = NULL) {
		if (isset(static::$stores[$name]))
			return static::$stores[$name];
		return static::$default;
	}

	/**
	 * Register cache store implementing `\MvcCore\Ext\ICache`.
	 * It could be file storage, redis storage, memcached storrage, etc...
	 * @param string              $name
	 * @param \MvcCore\Ext\ICache $store
	 * @param bool                $default
	 */
	public static function RegisterStore ($name, \MvcCore\Ext\ICache $store, $asDefault = FALSE) {
		static::$stores[$name] = $store;
		if ($asDefault)
			static::$default = $store;
	}

	/**
	 * Return `TRUE` if store with given name is registered.
	 * @param string $name
	 * @return bool
	 */
	public static function HasStore ($name) {
		return isset(static::$stores[$name]);
	}
}
