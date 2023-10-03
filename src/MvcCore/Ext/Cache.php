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

namespace MvcCore\Ext;

class Cache {

	/**
	 * MvcCore Extension - Form - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.2.0';

	/**
	 * Keys are store names, values are instances of `\MvcCore\Ext\Cache`.
	 * @var array
	 */
	protected static $stores = [];

	/**
	 * Default store.
	 * @var \MvcCore\Ext\Cache|NULL
	 */
	protected static $default = NULL;

	/**
	 * Get store by name or get store registered as default.
	 * @param string|NULL $name
	 * @return \MvcCore\Ext\Cache|NULL
	 */
	public static function GetStore ($name = NULL) {
		if (isset(static::$stores[$name]))
			return static::$stores[$name];
		if (static::$default !== NULL)
			return static::$default;
		$storeKeys = array_keys(static::$stores);
		return static::$stores[$storeKeys[0]];
	}

	/**
	 * Register cache store implementing `\MvcCore\Ext\ICache`.
	 * It could be file storage, redis storage, memcached storrage, etc...
	 * @param string				$name
	 * @param \MvcCore\Ext\Cache	$store
	 * @param bool					$default
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
