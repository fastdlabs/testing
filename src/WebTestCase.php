<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */

namespace FastD\Testing;


use FastD\Http\ServerRequest;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use FastD\Http\JsonResponse;

/**
 * Class TestCase
 * @package FastD\Testing
 */
abstract class WebTestCase extends TestCase
{
    use TestCaseTrait;

    /**
     * @return bool
     */
    abstract public function isLocal(): bool;

    /**
     *
     */
    public function setUp(): void
    {
        if ($this->isLocal()) {
            null != $this->getConnection() && parent::setUp();
        }
    }

    /**
     * Tear down unit
     */
    public function tearDown(): void
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
    public function createRequest(string $method, string $path, array $headers = []): ServerRequest
    {
        return new ServerRequest($method, $path, $headers);
    }

    /**
     * @deprecated
     * @param ResponseInterface $response
     * @param $assert
     */
    public function response(ResponseInterface $response, $assert): void
    {
        $this->equalsResponse($response, $assert);
    }


    /**
     * @param ResponseInterface $response
     * @param $assert
     */
    public function equalsResponse(ResponseInterface $response, $assert): void
    {
        $this->assertEquals((string) $response->getBody(), $assert);
    }

    /**
     * @param ResponseInterface $response
     */
    public function equalsResponseEmpty(ResponseInterface $response): void
    {
        $result = json_decode((string) $response->getBody(), true);
        $this->assertEmpty($result);
    }

    /**
     * @param ResponseInterface $response
     * @param $count
     */
    public function equalsResponseCount(ResponseInterface $response, int $count): void
    {
        $result = json_decode((string) $response->getBody(), true);
        $this->assertCount($count, $result);
    }

    /**
     * @deprecated
     * @param ResponseInterface $response
     * @param array $assert
     */
    public function json(ResponseInterface $response, array $assert): void
    {
        $this->equalsJson($response, $assert);
    }

    /**
     * @param ResponseInterface $response
     * @param array $assert
     */
    public function equalsJson(ResponseInterface $response, array $assert): void
    {
        $this->assertEquals((string) $response->getBody(), json_encode($assert, JsonResponse::JSON_OPTIONS));
    }

    /**
     * @param ResponseInterface $response
     * @param $key
     */
    public function equalsJsonResponseHasKey(ResponseInterface $response, string $key): void
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
    public function status(ResponseInterface $response, $statusCode): void
    {
        $this->equalsStatus($response, $statusCode);
    }

    /**
     * @param ResponseInterface $response
     * @param $statusCode
     */
    public function equalsStatus(ResponseInterface $response, $statusCode): void
    {
        $this->assertEquals($response->getStatusCode(), $statusCode);
    }

    /**
     * @param ResponseInterface $response
     */
    public function isServerInterval(ResponseInterface $response): void
    {
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isBadRequest(ResponseInterface $response): void
    {
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isNotFound(ResponseInterface $response): void
    {
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     */
    public function isSuccessful(ResponseInterface $response): void
    {
        $this->assertEquals(200, $response->getStatusCode());
    }
}
