<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Testing;


use FastD\Http\ServerRequest;
use PHPUnit_Extensions_Database_TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TestCase
 * @package FastD\Testing
 */
abstract class WebTestCase extends PHPUnit_Extensions_Database_TestCase
{
    const JSON_OPTION = JSON_UNESCAPED_UNICODE;

    public function isLocal()
    {
        $addr = gethostbyname(gethostname());
        return '127.0.0.1' === $addr;
    }

    /**
     * Set up unit.
     */
    public function setUp()
    {
        if ($this->isLocal()) {
            null != $this->getConnection() && parent::setUp();
        }
    }

    /**
     * Tear down unit
     */
    public function tearDown()
    {
        if ($this->isLocal()) {
            null != $this->getConnection() && parent::tearDown();
        }
    }

    /**
     * @param $method
     * @param $path
     * @param array $headers
     * @return ServerRequest
     */
    public function request($method, $path, array $headers = [])
    {
        $serverRequest = new ServerRequest($method, $path, $headers);

        return $serverRequest;
    }

    /**
     * @deprecated
     * @param ResponseInterface $response
     * @param $assert
     */
    public function response(ResponseInterface $response, $assert)
    {
        $this->equalsResponse($response, $assert);
    }


    /**
     * @param ResponseInterface $response
     * @param $assert
     */
    public function equalsResponse(ResponseInterface $response, $assert)
    {
        $this->assertEquals((string) $response->getBody(), $assert);
    }

    /**
     * @param ResponseInterface $response
     */
    public function equalsResponseEmpty(ResponseInterface $response)
    {
        $result = json_decode((string) $response->getBody(), true);
        $this->assertEmpty($result);
    }

    /**
     * @param ResponseInterface $response
     * @param $count
     */
    public function equalsResponseCount(ResponseInterface $response, $count)
    {
        $result = json_decode((string) $response->getBody(), true);
        $this->assertCount($count, $result);
    }

    /**
     * @deprecated
     * @param ResponseInterface $response
     * @param array $assert
     */
    public function json(ResponseInterface $response, array $assert)
    {
        $this->equalsJson($response, $assert);
    }

    /**
     * @param ResponseInterface $response
     * @param array $assert
     */
    public function equalsJson(ResponseInterface $response, array $assert)
    {
        $this->assertEquals((string) $response->getBody(), json_encode($assert, static::JSON_OPTION));
    }

    /**
     * @param ResponseInterface $response
     * @param $key
     */
    public function equalsJsonResponseHasKey(ResponseInterface $response, $key)
    {
        $json = (string) $response->getBody();
        $array = json_decode($json, true);
        if (is_string($key)) {
            $keys = [$key];
        } else {
            $keys = $key;
        }
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    /**
     * @deprecated
     * @param ResponseInterface $response
     * @param $statusCode
     */
    public function status(ResponseInterface $response, $statusCode)
    {
        $this->equalsStatus($response, $statusCode);
    }

    /**
     * @param ResponseInterface $response
     * @param $statusCode
     */
    public function equalsStatus(ResponseInterface $response, $statusCode)
    {
        $this->assertEquals($response->getStatusCode(), $statusCode);
    }

    /**
     * @param ResponseInterface $response
     */
    public function isServerInterval(ResponseInterface $response)
    {
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isBadRequest(ResponseInterface $response)
    {
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isNotFound(ResponseInterface $response)
    {
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isSuccessful(ResponseInterface $response)
    {
        $this->assertEquals(200, $response->getStatusCode());
    }
}