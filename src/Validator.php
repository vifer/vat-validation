<?php

namespace Vifer\VatValidation;

use InvalidArgumentException;
use Vifer\VatValidation\Utilities\TaxableCountries;

abstract class Validator {

	/**
	 * @var string|null
	 */
	protected $countryCode;

	/**
	 * @var string|null
	 */
	protected $vatNumber;

	/**
	 * Sanitises the VAT code by removing characters that cannot occur in a VAT number.
	 *
	 * @param string $vatNumber
	 * @return string
	 */
	public static function sanitiseVatNumber($vatNumber)
	{
		return preg_replace('~^[^A-Z0-9]$~', '', strtoupper($vatNumber));
	}

	/**
	 * @param string $countryCode
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setCountryCode($countryCode)
	{
		$this->validateCountry($countryCode);
		$this->countryCode = $countryCode;
		return $this;
	}

	/**
	 * @param string $vatNumber
	 * @return $this
	 */
	public function setVatNumber($vatNumber)
	{
		$this->vatNumber = static::sanitiseVatNumber($vatNumber);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		if (!$this->isValidPattern())
		{
			return false;
		}
		return $this->externalValidation();
	}

	/**
	 * @return bool
	 */
	protected function isValidPattern()
	{
		return (bool) preg_match('~^' . TaxableCountries::getVatCodePattern($this->countryCode) . '$~i', $this->vatNumber);
	}

	/**
	 * @return bool
	 */
	protected abstract function externalValidation();

	/**
	 * @param string $countryCode
	 * @return
	 */
	protected abstract function validateCountry($countryCode);
}