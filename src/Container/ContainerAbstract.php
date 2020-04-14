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

use sFire\DataControl\Translators\ArrayTranslator;
use sFire\DataControl\Translators\StringTranslator;
use sFire\Store\Exception\InvalidArgumentException;


/**
 * Class ContainerAbstract
 * @package sFire\Store
 */
abstract class ContainerAbstract implements ContainerInterface {


    /**
     * Contains the child classes instances
     * @var array
     */
    private static array $instances = [];


    /**
     * Contains all the stored data
     * @var array
     */
    private static array $data = [];


    /**
     * Contains the prefix of the child class
     * @var null|string
     */
    private ?string $prefix = null;


    /**
     * Contains instances of array translators
     * @var array
     */
    private static array $translators = [];


    /**
     * Constructor
     */
    public function __construct() {

        $this -> prefix = get_class($this);
        static::$data[$this -> prefix] = [];
    }


    /**
     * Returns an instance of the child class
     * @return self
     */
    public static function getInstance(): self {

        $class = get_called_class();

        if(false  === isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }


    /**
     * Stores a new piece of data and overwrites the data if already exists
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value): void {
        $this -> getTranslator(gettype($key)) -> set(static::$data[$this -> prefix], $key, $value);
    }


    /**
     * Stores a new piece of data and tries to merge the data if already exists
     * @param string|array $key
     * @param mixed $value
     * @return void
     */
    public function add($key, $value) {
        return $this -> getTranslator(gettype($key)) -> add(static::$data[$this -> prefix], $key, $value);
    }


    /**
     * Returns if an item exists
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool {
        return $this -> getTranslator(gettype($key)) -> has(static::$data[$this -> prefix], $key);
    }


    /**
     * Deletes all data
     * @return void
     */
    public function flush(): void {
        static::$data[$this -> prefix] = [];
    }


    /**
     * Removes data based on key
     * @param mixed $key
     * @return void
     */
    public function remove($key): void {
        $this -> getTranslator(gettype($key)) -> remove(static::$data[$this -> prefix], $key);
    }


    /**
     * Retrieves data based on key. Returns $default if not exists
     * @param mixed $key
     * @param mixed $default A default value which will be returned if the key is not found
     * @return mixed
     */
    public function get($key, $default = null) {

        if(false === is_string($key) && false === is_array($key) && false === is_numeric($key)) {
            throw new InvalidArgumentException(sprintf('Argument 1 passed to %s::%s() must be of the type string, float, integer or array, %s given', $this -> prefix, __FUNCTION__, gettype($key)));
        }

        return $this -> getTranslator(gettype($key)) -> get(static::$data[$this -> prefix], $key, $default = null);
    }


    /**
     * Retrieves and deletes an item. Returns $default if not exists
     * @param string|array $key
     * @param mixed $default A default value which will be returned if the key is not found
     * @return mixed
     */
    public function pull($key, $default = null) {
        return $this -> getTranslator(gettype($key)) -> get(static::$data[$this -> prefix], $key, $default = null);
    }


    /**
     * Retrieves all stored data
     * @return mixed
     */
    public function getAll() {
        return static::$data[$this -> prefix];
    }


    /**
     * Returns an array translator
     * @param string $type A different type of translator will be returned depending on the given type (string, integer, float and array)
     * @return mixed
     */
    private function getTranslator(string $type) {

        switch($type) {

            case 'string' :
            case 'integer' :
            case 'float' : return $this -> getStringTranslator(); break;
            case 'array' : return $this -> getArrayTranslator(); break;
        }

        throw new InvalidArgumentException(sprintf('Argument 1 passed to %s::%s() must be of the type string, float, integer or array, %s given', $this -> prefix, __FUNCTION__, gettype($type)));
    }


    /**
     * Returns an instance of StringTranslator
     * @return StringTranslator
     */
    private function getStringTranslator(): StringTranslator {

        if(false === isset(static::$translators['string'])) {
            static::$translators['string'] = new StringTranslator();
        }

        return static::$translators['string'];
    }


    /**
     * Returns an instance of ArrayTranslator
     * @return ArrayTranslator
     */
    private function getArrayTranslator(): ArrayTranslator {

        if(false === isset(static::$translators['array'])) {
            static::$translators['array'] = new ArrayTranslator();
        }

        return static::$translators['array'];
    }
}