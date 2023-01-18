<?php

namespace Przelewy24\Request;

use Przelewy24\Config;
use Przelewy24\Response\ResponseException;

abstract class Request {

	/**
	 * @var string
	 */
	protected string $endpoint;

	/**
	 * @var array
	 */
	private array $params;

	/**
	 * @var Config
	 */
	protected Config $config;

	function __construct( Config $config, $params = [] ) {
		$this->config = $config;
		$this->params = [];
		$this->setParams( $params );
		$this->setParams( $config->getRequestParams() );
		$this->signRequest();
	}

	/**
	 * Request parameter setter
	 *
	 * @param string $name
	 * @param $value
	 *
	 * @return void
	 */
	public function __set( string $name, $value ) {
		$this->params[ $name ] = $value;
	}

	/**
	 * Request parameter getter
	 *
	 * @param string $name
	 *
	 * @return mixed|string
	 */
	public function __get( string $name ) {
		return $this->params[ $name ] ?? '';
	}

	/**
	 * @param array $params
	 *
	 * @return void
	 */
	protected function setParams( array $params ) {
		$this->params = array_merge( $this->params, $params );
	}

	/**
	 * @return array
	 */
	protected function getParams(): array {
		return $this->params;
	}

	/**
	 * Set endpoint full Url
	 *
	 * @param string $endpoint
	 *
	 * @return void
	 */
	protected function setEndpoint( string $endpoint ) {
		$this->endpoint = $this->config->getBaseUrl() . $endpoint;
	}

	/**
	 * Get endpoint full Url
	 * @return string
	 */
	protected function getEndpoint(): string {
		return $this->endpoint;
	}

	/**
	 * @param $userParams
	 * @param $requiredParams
	 *
	 * @return void
	 */
	protected function checkRequiredParameters( $inputParams, $requiredParams ) {
		foreach ( $requiredParams as $param ) {
			if ( empty( $inputParams[ $param ] ) ) {
				throw new \InvalidArgumentException( $param . ' parameter is required.' );
			}
		}
	}

	/**
	 * Calculate signature hash and assign to the request 'sign' parameter
	 * @return void
	 */
	protected function signRequest() {
		$data       = [
			'sessionId'  => $this->sessionId,
			'merchantId' => (int) $this->merchantId,
			'amount'     => (int) $this->amount,
			'currency'   => $this->currency,
			'crc'        => $this->config->getCrc()
		];
		$signature  = json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$this->sign = hash( 'sha384', $signature );
	}

	/**
	 * Make HTTP request using cURL and return response array (status and data)
	 *
	 * @param string $method
	 *
	 * @return array
	 * @throws \Przelewy24\Request\RequestException
	 */
	protected function makeRequest( string $method ) {
		if ( ! in_array( $method, [ 'POST', 'PUT', 'GET' ] ) ) {
			throw new \Przelewy24\Request\RequestException( 'Method ' . $method . ' is not expected.' );
		}
		$curl = curl_init( $this->getEndpoint() );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
		curl_setopt( $curl, CURLOPT_HEADER, 0 );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->posId . ":" . $this->config->getReportKey() );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 20 );
		if ( $method !== 'GET' ) {
			curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $method );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $this->getParams(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $curl );
		$status   = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		curl_close( $curl );

		return [
			'request' => [
				$this->getEndpoint(),
				$this->getParams()
			],
			'status'  => $status,
			'data'    => json_decode( $response, true )
		];
	}

}