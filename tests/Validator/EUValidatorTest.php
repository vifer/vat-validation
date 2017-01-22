<?php

namespace Vifer\Tests\Validator;

use InvalidArgumentException;
use Mockery;
use PHPUnit_Framework_TestCase;
use SoapClient;
use stdClass;
use Vifer\VatValidation\Validator\EUValidator;

class EUValidatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider TaxableEUCountries
	 * @param string $countryCode
	 * @param string $vatNumber
	 * @param bool $isValid
	 */
	public function testValidate($countryCode, $vatNumber, $isValid)
	{
		$response = new stdClass();
		$response->valid = $isValid;
		$times = $isValid ? 1 : 0;

		$soapClientMock = Mockery::mock(SoapClient::class);
		$soapClientMock->shouldReceive('checkVat')
			->with($this->getVatData($countryCode, $vatNumber))
			->times($times)
			->andReturn($response);

		$vatNumberValidator =new EUValidator($soapClientMock);
		$vatNumberValidator->setCountryCode($countryCode)->setVatNumber($vatNumber);

		$this->assertEquals($isValid, $vatNumberValidator->isValid());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetCountryCodeThrowsAnException()
	{
		$noTaxableCountryCode = 'US';
		$vatNumberValidator =new EUValidator();
		$vatNumberValidator->setCountryCode($noTaxableCountryCode);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetCountryCodeWithNonEUCountryThrowsAnException()
	{
		$countryCode = 'IS';
		$vatNumberValidator =new EUValidator();
		$vatNumberValidator->setCountryCode($countryCode);
	}

	public function TaxableEUCountries()
	{
		return [
			'valid_belgium_vat_1' => [
				'iso_3166_2' => 'BE',
				'vat_code' => 'BE123456789',
				'is_valid' => true,
			],
			'valid_belgium_vat_2' => [
				'iso_3166_2' => 'BE',
				'vat_code' => '123456789',
				'is_valid' => true,
			],
			'invalid_belgium_vat' => [
				'iso_3166_2' => 'BE',
				'vat_code' => 'BE12345678901',
				'is_valid' => false,
			],
			'valid_austria_vat' => [
				'iso_3166_2' => 'AT',
				'vat_code' => 'U12345678',
				'is_valid' => true,
			],
			'invalid_austria_vat' => [
				'iso_3166_2' => 'AT',
				'vat_code' => 'AT12345678',
				'is_valid' => false,
			],
			'valid_gb_vat_1' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '150848196', // 9 long
				'is_valid' => true,
			],
			'valid_gb_vat_2' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GB150848196', // 9 long with country code
				'is_valid' => true,
			],
			'valid_gb_vat_3' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GB8888001GD', // EU Government Department
				'is_valid' => true,
			],
			'valid_gb_vat_4' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GB8888599HA', // EU Health Authority
				'is_valid' => true,
			],
			'valid_gb_vat_5' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GBGD001', // Short Government Department
				'is_valid' => true,
			],
			'valid_gb_vat_6' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GBHA599', // Short Health Authority
				'is_valid' => true,
			],
			'valid_gb_vat_7' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '150848114999', // 12 long
				'is_valid' => true,
			],
			'valid_gb_vat_8' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GB150848114', // our GB VAT
				'is_valid' => true,
			],
			'invalid_gb_vat_1' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '150848197', // Last 2 digits are above 96
				'is_valid' => false,
			],
			'invalid_gb_vat_3' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GBHA499', // // Health Authority code is less than 500
				'is_valid' => false,
			],
			'invalid_gb_vat_4' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '150848197999', // 12 digits with 8th and 9th digits are above 96
				'is_valid' => false,
			],
			'invalid_gb_vat_5' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '1508481978', // 10 chars
				'is_valid' => false,
			],
			'invalid_gb_vat_6' => [
				'iso_3166_2' => 'GB',
				'vat_code' => 'GBGD500', // Government Department code over 499
				'is_valid' => false,
			],
			'invalid_gb_vat_7' => [
				'iso_3166_2' => 'GB',
				'vat_code' => '150848114999A', // 13 long
				'is_valid' => false,
			],
		];
	}

	/**
	 * @param string $countryCode
	 * @param string $vatNumber
	 * @return array
	 */
	private function getVatData($countryCode, $vatNumber)
	{
		$countryCode = $countryCode == 'GR' ? 'EL' : $countryCode;
		$vatNumber = preg_replace('~^' . $countryCode . '$~', '', $vatNumber);
		return [
			'countryCode' => $countryCode,
			'vatNumber' => $vatNumber,
		];
	}
}