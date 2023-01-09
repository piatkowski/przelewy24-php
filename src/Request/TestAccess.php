<?php

namespace Przelewy24\Request;

use Przelewy24\Config;
use Przelewy24\Response\TestAccessResponse;

class TestAccess extends Request {

	function __construct( Config $config ) {
		parent::__construct( $config );
		$this->setEndpoint( '/api/v1/testAccess' );
	}

	/**
	 * Make test access GET request
	 * @return TestAccessResponse
	 * @throws ApiException
	 * @throws \Przelewy24\Response\ApiException
	 */
	function request(): TestAccessResponse {
		return new TestAccessResponse(
			parent::makeRequest( 'GET' )
		);
	}
}