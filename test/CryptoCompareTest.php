<?php

use PHPUnit\Framework\TestCase;
use CoinTokenHub\CryptoCompareApi\CryptoCompare;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareAuthException;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Class CryptoCompareTest
 */
class CryptoCompareTest extends TestCase {

	public function createHttpClient($statusCode, $body)
	{
		$mock = new MockHandler(
			array(
				new Response($statusCode, array(), $body)
			)
		);
		$handler = HandlerStack::create($mock);
		return new Client(array('handler' => $handler));
	}

	public function testRequestReturnsResponse() {
		$mockResponse = file_get_contents( __DIR__ . '/Mock/CryptoCompareTest/rate-limits-api-hours.txt' );
		$ccApi = new CryptoCompare($this->createHttpClient(200, $mockResponse));
		$this->assertEquals(
			json_decode($mockResponse),
			$ccApi->rateLimits("hours")
		);
	}



}