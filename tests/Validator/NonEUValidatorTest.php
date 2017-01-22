<?php

namespace Vifer\Tests\Validator;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Vifer\VatValidation\Validator\NonEUValidator;

class NonEUValidatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider TaxableNonEUCountries
	 * @param string $countryCode
	 * @param string $vatNumber
	 * @param bool $isValid
	 */
	public function testValidate($countryCode, $vatNumber, $isValid)
	{
		$vatNumberValidator = new NonEUValidator();
		$vatNumberValidator->setCountryCode($countryCode)->setVatNumber($vatNumber);

		$this->assertEquals($isValid, $vatNumberValidator->isValid());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetCountryCodeThrowsAnException()
	{
		$noTaxableCountryCode = 'US';
		$vatNumberValidator = new NonEUValidator();
		$vatNumberValidator->setCountryCode($noTaxableCountryCode);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetCountryCodeWithEUCountryThrowsAnException()
	{
		$countryCode = 'ES';
		$vatNumberValidator = new NonEUValidator();
		$vatNumberValidator->setCountryCode($countryCode);
	}

	public function TaxableNonEUCountries()
	{
		return [
			'valid_south_korea_vat' => [
				'iso_3166_2' => 'KR',
				'vat_code' => '1234567890',
				'is_valid' => true,
			],
			'invalid_south_korea_vat' => [
				'iso_3166_2' => 'KR',
				'vat_code' => '12345678',
				'is_valid' => false,
			],
			'valid_south_africa_vat_1' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => 'ZA4123456789',
				'is_valid' => true,
			],
			'valid_south_africa_vat_2' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => 'ZA40123456789',
				'is_valid' => true,
			],
			'valid_south_africa_vat_3' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => 'ZA3123456789',
				'is_valid' => true,
			],
			'valid_south_africa_vat_4' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => '3123456789',
				'is_valid' => true,
			],
			'invalid_south_africa_vat_1' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => 'ZA8012345678', // (ZA)?[3|4][0-9]{9,10}
				'is_valid' => false,
			],
			'invalid_south_africa_vat_2' => [
				'iso_3166_2' => 'ZA',
				'vat_code' => 'ZA412345678',
				'is_valid' => false,
			],
			'valid_new_zealand_vat_1' => [
				'iso_3166_2' => 'NZ',
				'vat_code' => '12345678',
				'is_valid' => true,
			],
			'valid_new_zealand_vat_2' => [
				'iso_3166_2' => 'NZ',
				'vat_code' => '123456789',
				'is_valid' => true,
			],
			'invalid_new_zealand_vat' => [
				'iso_3166_2' => 'NZ',
				'vat_code' => '1234567',
				'is_valid' => false,
			],
			'valid_switzerland_vat_1' => [
				'iso_3166_2' => 'CH',
				'vat_code' => 'CHE123456789MWST',
				'is_valid' => true,
			],
			'valid_switzerland_vat_2' => [
				'iso_3166_2' => 'CH',
				'vat_code' => 'CHE123456789TVA',
				'is_valid' => true,
			],
			'valid_switzerland_vat_3' => [
				'iso_3166_2' => 'CH',
				'vat_code' => 'CHE123456789IVA',
				'is_valid' => true,
			],
			'valid_switzerland_vat_4' => [
				'iso_3166_2' => 'CH',
				'vat_code' => '123456789IVA',
				'is_valid' => true,
			],
			'invalid_switzerland_vat_1' => [
				'iso_3166_2' => 'CH',
				'vat_code' => 'CHE123456IVA',
				'is_valid' => false,
			],
			'invalid_switzerland_vat_2' => [
				'iso_3166_2' => 'CH',
				'vat_code' => 'CHE123456789VAT',
				'is_valid' => false,
			],
			'valid_iceland_vat_1' => [
				'iso_3166_2' => 'IS',
				'vat_code' => 'IS12345',
				'is_valid' => true,
			],
			'valid_iceland_vat_2' => [
				'iso_3166_2' => 'IS',
				'vat_code' => 'IS123456',
				'is_valid' => true,
			],
			'valid_iceland_vat_3' => [
				'iso_3166_2' => 'IS',
				'vat_code' => 'IS1234567',
				'is_valid' => true,
			],
			'valid_iceland_vat_4' => [
				'iso_3166_2' => 'IS',
				'vat_code' => '1234567',
				'is_valid' => true,
			],
			'invalid_iceland_vat_1' => [
				'iso_3166_2' => 'IS',
				'vat_code' => 'IS1234',
				'is_valid' => false,
			],
			'invalid_iceland_vat_2' => [
				'iso_3166_2' => 'IS',
				'vat_code' => 'I123456',
				'is_valid' => false,
			],
		];
	}
}