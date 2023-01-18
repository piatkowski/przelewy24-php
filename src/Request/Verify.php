<?php

namespace Przelewy24\Request;

use Przelewy24\Config;
use Przelewy24\Response\VerifyResponse;

class Verify extends Request {

	function __construct( Config $config, $params ) {

		$this->checkRequiredParameters( $params, [
			'sessionId',
			'amount',
			'orderId'
		] );

		$defaultParams = [
			'currency' => 'PLN'
		];

		parent::__construct( $config, array_merge( $defaultParams, $params ) );

		$this->setEndpoint( '/api/v1/transaction/verify' );
	}

	/**
	 * Calculate signature hash and assign to the request 'sign' parameter
	 * @return void
	 */
	protected function signRequest() {
		$data       = [
			'sessionId' => $this->sessionId,
			'orderId'   => $this->orderId,
			'amount'    => (int) $this->amount,
			'currency'  => $this->currency,
			'crc'       => $this->config->getCrc()
		];
		$signature  = json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$this->sign = hash( 'sha384', $signature );
	}

	/**
	 * Make register transaction POST request
	 * @return VerifyResponse
	 * @throws RequestException
	 * @throws \Przelewy24\Response\ResponseException
	 */
	function request(): VerifyResponse {
		return new VerifyResponse(
			parent::makeRequest( 'PUT' )
		);
	}
}