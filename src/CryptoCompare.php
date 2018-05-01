<?php

namespace CoinTokenHub\CryptoCompareApi;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CryptoCompare {

	const PUBLIC_API_URL = 'https://min-api.cryptocompare.com/data/';

	const PUBLIC_API_CC_URL = 'https://www.cryptocompare.com/api/data/';

	private $httpClient;

	private $rateLimitTimeSpanOptions = array("hours", "seconds");


	public function __construct(Client $httpClient) {
		$this->httpClient = $httpClient;
	}


	/**
	 * @param $endpoint
	 * @param array $params
	 *
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function request($endpoint, $params = array())
	{
		$requestOptions = array();

		if ($params) {
			$requestOptions = array(
				"query" => $params
			);
		}

		$url = self::PUBLIC_API_URL . $endpoint;

		try {
			$request = $this->httpClient->request("GET", $url, $requestOptions);
			$response = $request->getBody();
			return json_decode($response->getContents());
		} catch (GuzzleException $e) {
			throw new CryptoCompareException("Exception while trying to make an API call", 1, $e);
		}

	}

	/**
	 * @param string $timeSpan
	 *
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function rateLimits($timeSpan = "seconds")
	{
		if (!in_array($timeSpan, $this->rateLimitTimeSpanOptions)) {
			throw new CryptoCompareException("Invalid timespan [".$timeSpan."] passed to method");
		}

		$endpoint = "";
		if ($timeSpan == "hours") {
			$endpoint = "stats/rate/hour/limit";
		} elseif ($timeSpan == "seconds") {
			$endpoint = "stats/rate/second/limit";
		}

		try {
			$limits = $this->request($endpoint);
			return $limits;
		} catch (CryptoCompareException $e) {
			throw new CryptoCompareException("Exception while trying to get rate limits", $e);
		}

	}
}