<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use sFire\Store\Container\ContainerAbstract;



class Config extends ContainerAbstract {
}

class Path extends ContainerAbstract {
}




final class ContainerAbstractTest extends TestCase {


    /**
     * Test if each class that extends the ContainerAbstract class will set and retrieve there own data
     * @return void
     */
    public function testOverlappingClasses(): void {

        $config = new Config();
        $config -> set('foo', 'bar');

        $this -> assertEquals('bar', $config -> get('foo'));
        $this -> assertEquals('bar', $config -> getInstance() -> get('foo'));
        $this -> assertEquals('bar', Config :: getInstance() -> get('foo'));


        $path = new Path();
        $path -> set('foo', 'quez');

        $this -> assertEquals('quez', $path -> get('foo'));
        $this -> assertEquals('quez', $path -> getInstance() -> get('foo'));
        $this -> assertEquals('quez', Path :: getInstance() -> get('foo'));

        $this -> assertEquals('bar', $config -> get('foo'));
    }


    public function testDifferentKindOfDataRetrieval() {

        $config = new Config();
        $config -> set('foo', ['bar' => true, 'ques' => [], 'waq' => 52, 25 => false]);


        var_dump($config -> get('foo.25'));
        exit();

        $this -> assertTrue($config -> get('foo.bar'));
        $this -> assertFalse($config -> get('foo.25'));
    }
}