<?php

namespace Przelewy24\Response;

abstract class Response {

	/**
	 * @var int     HTTP Response status code
	 */
	protected int $status;

	/**
	 * @var object  JSON response data object
	 */
	protected object $data;

	/**
	 * @param $response
	 *
	 * @throws ResponseException
	 */
	function __construct( $response ) {

		if ( ! isset( $response['status'] ) ) {
			throw new \InvalidArgumentException( 'Missing response status' );
		}
		if ( empty( $response['data'] ) ) {
			throw new ResponseException( 'JSON Response is empty' );
		}
		if ( (int) $response['status'] !== 200 ) {
			$error_message = '';
			if ( $response['data']['error'] ) {
				$error_message = ' ' . $response['data']['error'];
			}
			throw new ResponseException( 'Response status ' . $response['status'] . $error_message . '. Request ' . print_r($response['request'], true) );
		}
		$this->status = $response['status'];
		$this->data   = (object) $response['data'];
	}

	/**
	 * Get HTTP status code of Response
	 * @return int|mixed
	 */
	function getStatus() {
		return $this->status;
	}

	/**
	 * Get response data object
	 * @return object
	 */
	function getData() {
		return $this->data;
	}
}