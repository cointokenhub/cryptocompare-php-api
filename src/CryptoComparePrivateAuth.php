<?php

namespace CoinTokenHub\CryptoCompareApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareAuthException;

class CryptoComparePrivateAuth {

	const PRIVATE_API_AUTH_URL = 'https://www.cryptocompare.com/api/cryptopian/login/';

	private $httpClient;

	private $userName;

	private $password;

	public function __construct(Client $httpClient, $userName, $password) {
		$this->httpClient = $httpClient;
		$this->userName = $userName;
		$this->password = $password;
	}

	/**
	 * WARNING: this API is undocumented and could be changed by cryptocompare at anytime.
	 * SOURCE: https://www.reddit.com/r/cryptocompare/comments/7o83ds/api_how_can_i_access_private_api_data/dup0yg0/
	 * Authenticates with the private API and returns the sid that is to be used for subsequent requests.
	 * The sid will have to cached and re-used for all private requests.
	 * @return string
	 * @throws CryptoCompareAuthException
	 */
	public function authenticate() {
		try {
			$loginRequest = $this->httpClient->request('POST', self::PRIVATE_API_AUTH_URL, array(
				'body' => json_encode(array('Username' => $this->userName, 'Password' => $this->password)),
				'headers' => array(
					'content-type' => 'application/json',
					'accept' => 'application/json'
				)
			));
			$loginResponse = $loginRequest->getBody();
			$response = json_decode($loginResponse->getContents());
			if (isset($response->Response) && $response->Response == 'Error') {
				throw new CryptoCompareAuthException(
					"Encountered authentication exception while trying to login. Message from the API: " .
					"[".$response->Message."]");
			}

			$sid = $response->AuthCookie->Value;
			return $sid;
		} catch (GuzzleException $e) {
			throw new CryptoCompareAuthException(
				"Encountered Guzzle Exception while trying to authenticate",
				0,
				$e
			);
		}
	}

}