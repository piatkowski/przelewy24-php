<?php

namespace Przelewy24;

class Config {

	const BASE_URL_LIVE = 'https://secure.przelewy24.pl';
	const BASE_URL_SANDBOX = 'https://sandbox.przelewy24.pl';

	/**
	 * @var string
	 */
	private string $posId;

	/**
	 * @var string
	 */
	private string $merchantId;

	/**
	 * @var string
	 */
	private string $crc;

	/**
	 * API KEY
	 * @var string
	 */
	private string $reportKey;

	/**
	 * @var string|bool
	 */
	private string $isSandbox;

	/**
	 * Create config with at least merchantId or posId, crc, reportKey and sandbox mode (true/false).
	 *
	 * @param $params
	 */
	function __construct( $params ) {
		$this->merchantId = $params['merchantId'] ?? $params['posId'] ?? '';
		$this->posId      = $params['posId'] ?? $params['merchantId'] ?? '';
		$this->crc        = $params['crc'] ?? '';
		$this->isSandbox  = isset( $params['sandbox'] ) && $params['sandbox'] === true;
		$this->reportKey  = $params['reportKey'] ?? '';

		if ( ( $this->merchantId === '' && $this->posId === '' ) ) {
			throw new \InvalidArgumentException( 'You need to specify merchantId or posId' );
		}

		if ( $this->crc === '' ) {
			throw new \InvalidArgumentException( 'You need to specify CRC' );
		}

		if ( $this->reportKey === '' ) {
			throw new \InvalidArgumentException( 'You need to specify Report Key' );
		}
	}

	/**
	 * Get merchantId parameter
	 * @return string
	 */
	function getMerchantId(): string {
		return $this->merchantId;
	}

	/**
	 * Get posId parameter
	 * @return string
	 */
	function getPosId(): string {
		return $this->posId ?? $this->merchantId;
	}

	/**
	 * Get CRC parameter
	 * @return string
	 */
	function getCrc(): string {
		return $this->crc;
	}

	/**
	 * Get reportKey parameter (API KEY)
	 * @return string
	 */
	function getReportKey(): string {
		return $this->reportKey;
	}

	/**
	 * Determine if using sandbox or live mode
	 * @return bool
	 */
	function isSandbox(): bool {
		return $this->isSandbox;
	}

	/**
	 * Get required parameters for making Request.
	 * Don't need to pass CRC or ReportKey in Request
	 * @return array
	 */
	function getRequestParams(): array {
		return [
			'merchantId' => $this->getMerchantId(),
			'posId'      => $this->getPosId()
		];
	}

	/**
	 * Get base API url (sandbox or live)
	 * @return string
	 */
	function getBaseUrl(): string {
		return $this->isSandbox() ? self::BASE_URL_SANDBOX : self::BASE_URL_LIVE;
	}

}