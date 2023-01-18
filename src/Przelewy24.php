<?php

namespace Przelewy24;

use Przelewy24\Request\TestAccess;
use Przelewy24\Request\Transaction;
use Przelewy24\Request\Verify;
use Przelewy24\Response\TestAccessResponse;
use Przelewy24\Response\TransactionResponse;
use Przelewy24\Response\VerifyResponse;

class Przelewy24 {

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param array $parameters
	 *
	 * @throws \Exception
	 */
	function __construct( array $parameters ) {
		if ( ! extension_loaded( 'curl' ) ) {
			throw new \Exception( 'Extension cURL is disabled.' );
		}
		$this->config = new Config( $parameters );
	}

	/**
	 * Request new transaction. The response allows to get payment redirect URL
	 * Use getRedirectUrl() on response to get payment URL
	 *
	 * @param array $params
	 *
	 * @return TransactionResponse
	 * @throws Request\RequestException
	 * @throws Response\ResponseException
	 */
	function transaction( array $params ): TransactionResponse {
		$transaction = new Transaction( $this->config, $params );

		return $transaction->request();
	}

	/**
	 * Test P24 Api Access
	 * Use getStatus() and getData() on response
	 * @return TestAccessResponse
	 * @throws Request\RequestException
	 * @throws Response\ResponseException
	 */
	function testAccess(): TestAccessResponse {
		$testAccess = new TestAccess( $this->config );

		return $testAccess->request();
	}

	/**
	 * Verify transaction - true on success, false on error
	 *
	 * @param array $params
	 *
	 * @return VerifyResponse
	 * @throws Request\RequestException
	 * @throws Response\ResponseException
	 */
	function verify( Notification $notification ): VerifyResponse {
		$verify = new Verify( $this->config, $notification->getParameters( [
			'sessionId',
			'amount',
			'orderId'
		] ) );

		return $verify->request();
	}

	/**
	 * Receive Payment Notification from P24
	 * @return Notification
	 */
	function receiveNotification(): Notification {
		$jsonData = file_get_contents( 'php://input' );
		$data     = json_decode( $jsonData, true );

		return new Notification( $data ?? [] );
	}

	/**
	 * Create Notification object from raw data
	 *
	 * @param array $params
	 *
	 * @return Notification
	 */
	function createNotification( array $params ): Notification {
		return new Notification( [
			'merchantId' => $this->config->getMerchantId(),
			'posId'      => $this->config->getPosId(),
			'sessionId'  => $params['sessionId'],
			'amount'     => $params['amount'],
			'currency'   => $params['currency'] ?? 'PLN',
			'orderId'    => $params['amount']
		] );
	}
}