<?php

namespace Vifer\VatValidation\Validator;

use InvalidArgumentException;
use Vifer\VatValidation\Utilities\TaxableCountries;
use Vifer\VatValidation\Validator;

class ValidatorFactory {

	/**
	 * @param string $countryIsoCode
	 * @return Validator
	 * @throws InvalidArgumentException
	 */
	public function makeValidator($countryIsoCode)
	{
		if (TaxableCountries::isEU($countryIsoCode))
		{
			$validator = new EUValidator();
		}
		else
		{
			$validator = new NonEUValidator();
		}

		return $validator->setCountryCode($countryIsoCode);
	}
}
