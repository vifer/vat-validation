<?php

namespace Vifer\Tests\Validator;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Vifer\VatValidation\Utilities\TaxableCountries;
use Vifer\VatValidation\Validator\EUValidator;
use Vifer\VatValidation\Validator\NonEUValidator;
use Vifer\VatValidation\Validator\ValidatorFactory;

class ValidatorFactoryTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider taxableCountryProvider
	 * @param string $isoCode
	 */
	public function testMakeValidator($isoCode)
	{
		$validator = (new ValidatorFactory())->makeValidator($isoCode);
		if (TaxableCountries::isEU($isoCode))
		{
			$this->assertInstanceOf(EUValidator::class, $validator);
		}
		else
		{
			$this->assertInstanceOf(NonEUValidator::class, $validator);
		}
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMakeValidatorThrowsAnException()
	{
		$noTaxableCountryCode = 'US';
		(new ValidatorFactory())->makeValidator($noTaxableCountryCode);
	}

	public function taxableCountryProvider()
	{
		return [
			'EU_country' => ['ES'],
			'NonEU_country' => ['IS'],
		];
	}
}