<?php

use PHPUnit\Framework\TestCase;
use CoinTokenHub\CryptoCompareApi\CryptoCompare;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Class CryptoCompareTest
 */
class CryptoCompareTest extends TestCase {

	private function createHttpClientAndQueueResponse($statusCode, $body)
	{
		$mock = new MockHandler(
			array(
				new Response($statusCode, array(), $body)
			)
		);
		$handler = HandlerStack::create($mock);
		return new Client(array('handler' => $handler));
	}

	private function createHttpClientAndThrowException()
	{
		$mock = new MockHandler(
			array(
				new RequestException(
					"Error communicating with the server",
					new Request('GET', 'test')
				)
			)
		);
		$handler = HandlerStack::create($mock);
		return new Client(array('handler' => $handler));
	}

	public function testRequestReturnsResponse()
	{
		$mockResponse = file_get_contents( __DIR__ . '/Mock/CryptoCompareTest/rate-limits-api-hours.txt' );
		$ccApi = new CryptoCompare($this->createHttpClientAndQueueResponse(200, $mockResponse));
		$this->assertEquals(
			json_decode($mockResponse),
			$ccApi->request("stats/rate/hour/limit")
		);
	}


	public function testRequestThrowsException()
	{
		$ccApi = new CryptoCompare($this->createHttpClientAndThrowException());
		$this->expectException(CryptoCompareException::class);
		$ccApi->request("stats/rate/hour/limit");
	}


	public function testItReturnsRateLimitsForHours()
	{
		$mockResponse = file_get_contents( __DIR__ . '/Mock/CryptoCompareTest/rate-limits-api-hours.txt' );
		$ccApi = new CryptoCompare($this->createHttpClientAndQueueResponse(200, $mockResponse));
		$this->assertEquals(
			json_decode($mockResponse),
			$ccApi->rateLimits("hours")
		);
	}

	public function testItReturnsRateLimitsForSeconds()
	{
		$mockResponse = file_get_contents( __DIR__ . '/Mock/CryptoCompareTest/rate-limits-api-seconds.txt' );
		$ccApi = new CryptoCompare($this->createHttpClientAndQueueResponse(200, $mockResponse));
		$this->assertEquals(
			json_decode($mockResponse),
			$ccApi->rateLimits("seconds")
		);
	}

	public function testItThrowsExceptionForInvalidRateLimitTimeParameter()
	{
		$ccApi = new CryptoCompare($this->createHttpClientAndQueueResponse(200, ""));
		$this->expectException(CryptoCompareException::class);
		$ccApi->rateLimits("INVALID");
	}

}