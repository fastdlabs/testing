<?php

use FastD\Testing\WebTestCase;
use PHPUnit\DbUnit\Database\DataSet;


/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class TestCase extends WebTestCase
{
    static private $pdo = null;

    private $conn = null;

    /**
     * @return null|\PHPUnit\DbUnit\Database\Connection|\PHPUnit\DbUnit\Database\DefaultConnection
     */
    protected function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('mysql:host=127.0.0.1;dbname=ci', 'travis');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
    }

    /**
     * @return DataSet|\PHPUnit\DbUnit\DataSet\IDataSet
     */
    protected function getDataSet()
    {
        return new DataSet(
            __DIR__ . "/dataset/guestbook.yml"
        );
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return true;
    }
}