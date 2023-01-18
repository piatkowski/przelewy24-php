<?php

namespace Przelewy24;

class Notification {

	protected array $parameters = [];

	protected function getAllowedParameters(): array {
		return [
			'merchantId',
			'posId',
			'sessionId',
			'amount',
			'originAmount',
			'currency',
			'orderId',
			'methodId',
			'statement',
			'sign'
		];
	}

	function __construct( array $parameters ) {
		if ( ! empty( $parameters ) ) {
			foreach ( $this->getAllowedParameters() as $key ) {
				$this->parameters[ $key ] = $parameters[ $key ] ?? '';
			}
		}
	}

	function __get( $name ) {
		return $this->parameters[ $name ] ?? '';
	}

	function getParameters( array $keys = [] ) {
		if ( empty( $keys ) ) {
			return $this->parameters;
		}

		return array_filter( $this->parameters, function ( $value, $key ) use ( $keys ) {
			return in_array( $key, $keys );
		}, ARRAY_FILTER_USE_BOTH );
	}

}