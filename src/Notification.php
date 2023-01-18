<?php

namespace Przelewy24;

/**
 * Payment Notification
 */
class Notification {

	/**
	 * Notification parameters
	 * @var array
	 */
	protected array $parameters = [];

	/**
	 * Returns array of allowed parameter keys
	 * @return string[]
	 */
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

	/**
	 * Get parameters by keys. Returns all paraeters if $keys is empty
	 * @param array $keys
	 *
	 * @return array
	 */
	function getParameters( array $keys = [] ): array {
		if ( empty( $keys ) ) {
			return $this->parameters;
		}

		return array_filter( $this->parameters, function ( $value, $key ) use ( $keys ) {
			return in_array( $key, $keys );
		}, ARRAY_FILTER_USE_BOTH );
	}

}