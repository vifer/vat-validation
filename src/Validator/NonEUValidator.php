<?php

namespace Vifer\VatValidation\Validator;

use InvalidArgumentException;
use Vifer\VatValidation\Utilities\TaxableCountries;
use Vifer\VatValidation\Validator;

class NonEUValidator extends Validator {

	/**
	 * @inheritdoc
	 */
	protected function externalValidation()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	protected function validateCountry($countryCode)
	{
		if (TaxableCountries::isEU($countryCode))
		{
			throw new InvalidArgumentException('Non EU country expected');
		}
	}
}