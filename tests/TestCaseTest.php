<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Testing\TestCase;


class TestCaseTest extends PHPUnit_Framework_TestCase
{
    public function testTestCaseFakeData()
    {
        $testing = new TestCase();

        print_r($testing);
    }
}
