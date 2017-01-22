<?php

namespace Vifer\VatValidation\Validator;

use Exception;
use InvalidArgumentException;
use SoapClient;
use SoapFault;
use Vifer\VatValidation\Utilities\TaxableCountries;
use Vifer\VatValidation\Validator;

class EUValidator extends Validator {

	const SOAP_CLIENT_TIMEOUT_SECONDS = 5;

	/**
	 * @var SoapClient
	 */
	private $soapClient;

	/**
	 * @param null|SoapClient $soapClient
	 */
	public function __construct(SoapClient $soapClient = null)
	{
		$this->soapClient = $soapClient;
		if (is_null($this->soapClient))
		{
			$this->setDefaultSoapClient();
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function externalValidation()
	{
		if (!$this->isServiceAvailable())
		{
			return true;
		}
		$checkVatData = $this->buildDataForExternalValidation();
		// if we can't contact the server, just allow the code.
		return $this->sendDataOffToExternallyValidate($checkVatData);
	}

	/**
	 * @inheritdoc
	 */
	protected function validateCountry($countryCode)
	{
		if (!TaxableCountries::isEU($countryCode))
		{
			throw new InvalidArgumentException('EU country expected');
		}
	}

	private function setDefaultSoapClient()
	{
		try
		{
			$streamContext = stream_context_create(['http' => ['timeout' => static::SOAP_CLIENT_TIMEOUT_SECONDS]]);
			$this->soapClient = new SoapClient('http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl',
				[
					'connection_timeout' => static::SOAP_CLIENT_TIMEOUT_SECONDS, // Connection timeout
					'stream_context' => $streamContext, // Timeout once the connection is open
				]);
		}
		catch (SoapFault $e)
		{
			$this->soapClient = null;
		}
	}

	/**
	 * Determines if the soap service is up or down
	 * @return bool
	 */
	private function isServiceAvailable()
	{
		return !is_null($this->soapClient);
	}

	/**
	 * @return array
	 */
	private function buildDataForExternalValidation()
	{
		// greece has iso code GR, but vat code EL
		$countryCode = $this->countryCode == 'GR' ? 'EL' : $this->countryCode;
		return [
			'countryCode' => $countryCode,
			'vatNumber' => preg_replace('~^' . $countryCode . '$~', '', $this->vatNumber),
		];
	}

	/**
	 * if we can't contact the server, just allow the code.
	 * @param array $checkVatData
	 * @return bool
	 */
	private function sendDataOffToExternallyValidate($checkVatData)
	{
		try
		{
			$result = $this->soapClient->checkVat($checkVatData);
			return $result->valid;
		}
		catch (Exception $e)
		{
			return true;
		}
	}
}