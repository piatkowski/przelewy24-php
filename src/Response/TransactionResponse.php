<?php

namespace Przelewy24\Response;

class TransactionResponse extends Response {

	/**
	 * @var string  Base payment gateway Url
	 */
	private string $baseUrl = '';

	/**
	 * Set case payment gateway Url
	 * @param $url
	 *
	 * @return void
	 */
	function setBaseUrl( $url ) {
		$this->baseUrl = rtrim( $url, '/' );
	}

	/**
	 * Get redirect Url for payment
	 * @return string
	 * @throws ApiException
	 */
	function getRedirectUrl() {
		if ( ! isset( $this->getData()->data['token'] ) ) {
			throw new ApiException( 'Cannot get redirect url - missing transaction token.' );
		}
		return $this->baseUrl . '/' . $this->getData()->data['token'];
	}
}