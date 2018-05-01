<?php
namespace CoinTokenHub\CryptoCompareApi\Coin;

use CoinTokenHub\CryptoCompareApi\CryptoCompare;
use CoinTokenHub\CryptoCompareApi\Exception\CryptoCompareException;

class Coin extends CryptoCompare
{
	const COINLIST_ENDPOINT = "coinlist";
	const COINSNAPSHOT_ENDPOINT = "coinsnapshot";
	const COINSNAPSHOTFULLBYID_ENDPOINT = "coinsnapshotfullbyid";
	const SOCIALSTATS_ENDPOINT = "socialstats";

	/**
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function coinList()
	{
		try {
			$coins = $this->request(self::COINLIST_ENDPOINT);
			return $coins;
		} catch (CryptoCompareException $e) {
			throw new CryptoCompareException("Exception while trying to get coinList", $e);
		}
	}

	/**
	 * @param string $fsym
	 * @param string $tsym
	 *
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function coinSnapshot($fsym = "BTC", $tsym = "USD")
	{
		if (empty($fsym) || empty($tsym)) {
			throw new CryptoCompareException("Invalid method invocation. Parameters `fsym` or `tsym` " .
			                                 "cannot be empty");
		}

		$params = array(
			"fsym" => $fsym,
			"tsym" => $tsym
		);

		try {
			$snapshot = $this->request(self::COINSNAPSHOT_ENDPOINT, $params);
			return $snapshot;
		} catch (CryptoCompareException $e) {
			throw new CryptoCompareException("Exception while trying to get coinSnapshot", $e);
		}
	}

	/**
	 * @param $id
	 *
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function coinSnapshotFullById($id)
	{
		if (empty($id)) {
			throw new CryptoCompareException("Invalid method invocation. Parameter `id` " .
			                                 "cannot be empty");
		}

		$params = array(
			"id" => (int) $id
		);

		try {
			$snapshot = $this->request(self::COINSNAPSHOTFULLBYID_ENDPOINT, $params);
			return $snapshot;
		} catch (CryptoCompareException $e) {
			throw new CryptoCompareException("Exception while trying to get coinSnapshotFullById", $e);
		}
	}

	/**
	 * @param $id
	 *
	 * @return \stdClass
	 * @throws CryptoCompareException
	 */
	public function socialStats($id)
	{
		if (empty($id)) {
			throw new CryptoCompareException("Invalid method invocation. Parameter `id` " .
			                                 "cannot be empty");
		}

		$params = array(
			"id" => (int) $id
		);

		try {
			$socialStats = $this->request(self::SOCIALSTATS_ENDPOINT, $params);
			return $socialStats;
		} catch (CryptoCompareException $e) {
			throw new CryptoCompareException("Exception while trying to get coinSnapshotFullById", $e);
		}

	}
}