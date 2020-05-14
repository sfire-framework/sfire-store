<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Store\Container;


/**
 * Interface ContainerInterface
 * @package sFire\Store
 */
interface ContainerInterface {


	/**
	 * Stores a new piece of data and tries to merge the data if already exists
	 * @param string|array $key
	 * @param mixed $value
	 * @return bool if value has been set
	 */
	public function add($key, $value);

	
	/**
	 * Stores a new piece of data and overwrites the data if already exists
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function set($key, $value): void;


	/**
	 * Returns if an item exists
	 * @param mixed $key
	 * @return bool
	 */
	public function has($key): bool;


	/**
	 * Deletes all data
	 * @return void
	 */
	public function flush(): void;


	/**
	 * Removes data based on key
	 * @param mixed $key
	 * @return void
	 */
	public function remove($key): void;


	/**
	 * Retrieves data based on key. Returns $default if not exists
	 * @param mixed $key
	 * @param mixed $default A default value which will be returned if the key is not found
	 * @return mixed
	 */
	public function get($key, $default = null);


	/**
	 * Retrieves and deletes an item. Returns $default if not exists
	 * @param string|array $key
	 * @param mixed $default A default value which will be returned if the key is not found
	 * @return mixed
	 */
	public function pull($key, $default = null);


	/**
	 * Retrieves all stored data
	 * @return mixed
	 */
	public function getAll();
}