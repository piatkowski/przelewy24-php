<?php

namespace Przelewy24;

use Przelewy24\Request\TestAccess;
use Przelewy24\Request\Transaction;
use Przelewy24\Response\TestAccessResponse;
use Przelewy24\Response\TransactionResponse;

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
	 * @throws Request\ApiException
	 * @throws Response\ApiException
	 */
	function transaction( array $params ) : TransactionResponse {
		$transaction = new Transaction( $this->config, $params );
		return $transaction->request();
	}

	/**
	 * Test P24 Api Access
	 * Use getStatus() and getData() on response
	 * @return TestAccessResponse
	 * @throws Request\ApiException
	 * @throws Response\ApiException
	 */
	function testAccess() : TestAccessResponse {
		$testAccess = new TestAccess( $this->config );
		return $testAccess->request();
	}
}