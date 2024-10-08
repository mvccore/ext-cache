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

interface ICache {

	/**
	 * Connection `name` key for constructor configuration array.
	 * @var string
	 */
	const CONNECTION_NAME		= 'name';

	/**
	 * Connection `host` key for constructor configuration array.
	 * @var string
	 */
	const CONNECTION_HOST		= 'host';

	/**
	 * Connection `port` key for constructor configuration array.
	 * @var string
	 */
	const CONNECTION_PORT		= 'port';

	/**
	 * Connection `persistence` name key for constructor configuration array.
	 * @var string
	 */
	const CONNECTION_PERSISTENCE= 'persistence';

	/**
	 * Connection `database` name key for constructor configuration array.
	 * @var string
	 */
	const CONNECTION_DATABASE	= 'database';

	/**
	 * Connection `timeout` key for constructor configuration array, in miliseconds (1s = 1000ms).
	 * @var string
	 */
	const CONNECTION_TIMEOUT	= 'timeout';

	/**
	 * Connection `provider` key for constructor configuration array.
	 * @var string
	 */
	const PROVIDER_CONFIG		= 'provider';

	/**
	 * Cache tag records prefix: `cache.tag.`.
	 * @var string
	 */
	const TAG_PREFIX			= 'cache.tag.';

	/**
	 * Create or get cached cache wrapper instance.
	 * @param  string|array|NULL $connectionArguments...
	 * If string, it's used as connection name.
	 * If array, it's used as connection config array with keys:
	 *  - `name`		default: 'default'
	 *  - `host`		default: '127.0.0.1'
	 *  - `port`		default: depends on implementation
	 *  - `database`	default: $_SERVER['SERVER_NAME']
	 *  - `timeout`		default: NULL, in miliseconds (1s = 1000ms)
	 *  If NULL, there is returned `default` connection
	 *  name with default initial configuration values.
	 * @return \MvcCore\Ext\Caches\Base
	 */
	public static function GetInstance (/*...$connectionNameOrArguments = NULL*/);

	/**
	 * Connect to redis server by configuration given in constructor.
	 * Return boolean about connection success (not about cache enabled or disabled).
	 * @return bool
	 */
	public function Connect ();

	/**
	 * Get provider instance.
	 * @return object|NULL
	 */
	public function GetProvider();

	/**
	 * Set provider instance.
	 * @param  object|NULL $provider
	 * @return \MvcCore\Ext\Caches\Base
	 */
	public function SetProvider ($provider);

	/**
	 * Return initial configuration data.
	 * @return \stdClass
	 */
	public function GetConfig ();

	/**
	 * Enable/disable cache wrapper.
	 * @param  bool $enable
	 * @return \MvcCore\Ext\Caches\Base
	 */
	public function SetEnabled ($enabled);

	/**
	 * Get if cache wrapper is enabled/disabled.
	 * @return bool
	 */
	public function GetEnabled ();

	/**
	 * Set content under key with seconds expiration and tag(s).
	 * @param  string   $key
	 * @param  mixed    $content
	 * @param  int|NULL $expirationSeconds
	 * @param  array    $cacheTags
	 * @return bool
	 */
	public function Save ($key, $content, $expirationSeconds = NULL, $cacheTags = []);

	/**
	 * Set multiple contents under keys with seconds expirations and tags.
	 * @param  array    $keysAndContents
	 * @param  int|NULL $expirationSeconds
	 * @param  array    $cacheTags
	 * @return bool
	 */
	public function SaveMultiple ($keysAndContents, $expirationSeconds = NULL, $cacheTags = []);

	/**
	 * Return mixed content from cache by key or return `NULL` if content doens't
	 * exist in cache for given key.
	 * @param  string        $key
	 * @param  callable|NULL $notFoundCallback function ($cache, $cacheKey) { ... $cache->Save($cacheKey, $data); return $data; }
	 * @throws \Exception    Not found callback is not callable.
	 * @return mixed|NULL
	 */
	public function Load ($key, $notFoundCallback = NULL);

	/**
	 * Get content by key.
	 * @param  \string[]     $keys
	 * @param  callable|NULL $notFoundCallback function ($cache, $cacheKey) { ... $cache->Save($cacheKey, $data); return $data; }
	 * @throws \Exception    Not found callback is not callable.
	 * @return array|NULL
	 */
	public function LoadMultiple (array $keys, $notFoundCallback = NULL);

	/**
	 * Delete cache record by key.
	 * @param  string $key
	 * @return bool
	 */
	public function Delete ($key);

	/**
	 * Delete cache record(s) by key(s).
	 * @param  array $keys
	 * @param  array $keysTags
	 * @return int
	 */
	public function DeleteMultiple (array $keys, array $keysTags = []);

	/**
	 * Delete cache record by key.
	 * @param  string|array $tags
	 * @return int
	 */
	public function DeleteByTags ($tags);

	/**
	 * Return `1` if cache has any record under given key, `0` if not.
	 * @param  string $key
	 * @return bool
	 */
	public function Has ($key);

	/**
	 * Return `1` if cache has any record under given key, `0` if not.
	 * @param  string|\string[] $keys
	 * @return int
	 */
	public function HasMultiple ($keys);

	/**
	 * Remove everything from used cache database.
	 * @return bool
	 */
	public function Clear ();
}
