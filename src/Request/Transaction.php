<?php

namespace Przelewy24\Request;

use Przelewy24\Config;
use Przelewy24\Response\Response;
use Przelewy24\Response\TransactionResponse;

class Transaction extends Request {

	function __construct( Config $config, $params ) {

		$this->checkRequiredParameters( $params, [
			'sessionId',
			'amount',
			'description',
			'email',
			'urlReturn'
		] );

		$defaultParams = [
			'currency' => 'PLN',
			'country'  => 'PL',
			'language' => 'pl'
		];

		parent::__construct( $config, array_merge( $defaultParams, $params ) );

		$this->setEndpoint( '/api/v1/transaction/register' );
	}

	/**
	 * Make register transaction POST request
	 * @return TransactionResponse
	 * @throws ApiException
	 * @throws \Przelewy24\Response\ApiException
	 */
	function request(): TransactionResponse {
		$response = new TransactionResponse(
			parent::makeRequest( 'POST' )
		);
		$response->setBaseUrl( $this->config->getBaseUrl() . '/trnRequest' );

		return $response;
	}
}