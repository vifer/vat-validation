<?php

namespace Vifer\VatValidation\Utilities;

use InvalidArgumentException;

class TaxableCountries {

	/**
	 * @var array
	 */
	private static $taxableCountries = [
		'AT' => [
			'iso_3166_2' => 'AT',
			'pattern' => '(AT)?U[\d]{8}',
			'is_eu' => true,
		],
		'BE' => [
			'iso_3166_2' => 'BE',
			'pattern' => '(BE)?[\d]{9,10}',
			'is_eu' => true,
		],
		'BG' => [
			'iso_3166_2' => 'BG',
			'pattern' => '(BG)?[\d]{9,10}',
			'is_eu' => true,
		],
		'HR' => [
			'iso_3166_2' => 'HR',
			'pattern' => '(HR)?[\d]{11}',
			'is_eu' => true,
		],
		'CZ' => [
			'iso_3166_2' => 'CZ',
			'pattern' => '(CZ)?[\d]{8,10}',
			'is_eu' => true,
		],
		'DK' => [
			'iso_3166_2' => 'DK',
			'pattern' => '(DK)?[\d]{8}',
			'is_eu' => true,
		],
		'EE' => [
			'iso_3166_2' => 'EE',
			'pattern' => '(EE)?[\d]{9}',
			'is_eu' => true,
		],
		'FI' => [
			'iso_3166_2' => 'FI',
			'pattern' => '(FI)?[\d]{8}',
			'is_eu' => true,
		],
		'FR' => [
			'iso_3166_2' => 'FR',
			'pattern' => '(FR)?[0-9A-HJ-NP-Z]{2}[\d]{9}',
			'is_eu' => true,
		],
		'DE' => [
			'iso_3166_2' => 'DE',
			'pattern' => '(DE)?[\d]{9}',
			'is_eu' => true,
		],
		'EL' => [
			'iso_3166_2' => 'EL',
			'pattern' => '(EL)?[\d]{9}',
		],
		'HU' => [
			'iso_3166_2' => 'HU',
			'pattern' => '(HU)?[\d]{8}',
			'is_eu' => true,
		],
		'IE' => [
			'iso_3166_2' => 'IE',
			'pattern' => '(IE)?([\d]{7}[A-Z]{1,2}|[\d][A-Z][\d]{5}[A-Z])W?',
			'is_eu' => true,
		],
		'IT' => [
			'iso_3166_2' => 'IT',
			'pattern' => '(IT)?[\d]{11}',
			'is_eu' => true,
		],
		'LV' => [
			'iso_3166_2' => 'LV',
			'pattern' => '(LV)?[\d]{11}',
			'is_eu' => true,
		],
		'LT' => [
			'iso_3166_2' => 'LT',
			'pattern' => '(LT)?([\d]{9}|[\d]{12})',
			'is_eu' => true,
		],
		'LU' => [
			'iso_3166_2' => 'LU',
			'pattern' => '(LU)?[\d]{8}',
			'is_eu' => true,
		],
		'MT' => [
			'iso_3166_2' => 'MT',
			'pattern' => '(MT)?[\d]{8}',
			'is_eu' => true,
		],
		'NL' => [
			'iso_3166_2' => 'NL',
			'pattern' => '(NL)?[\d]{9}B[\d]{2}',
			'is_eu' => true,
		],
		'PL' => [
			'iso_3166_2' => 'PL',
			'pattern' => '(PL)?[\d]{10}',
			'is_eu' => true,
		],
		'PT' => [
			'iso_3166_2' => 'PT',
			'pattern' => '(PT)?[\d]{9}',
			'is_eu' => true,
		],
		'RO' => [
			'iso_3166_2' => 'RO',
			'pattern' => '(RO)?[\d]{2,10}',
			'is_eu' => true,
		],
		'SK' => [
			'iso_3166_2' => 'SK',
			'pattern' => '(SK)?[\d]{10}',
			'is_eu' => true,
		],
		'SI' => [
			'iso_3166_2' => 'SI',
			'pattern' => '(SI)[\d]{8}',
			'is_eu' => true,
		],
		'ES' => [
			'iso_3166_2' => 'ES',
			'pattern' => '(ES)?([0-9A-Z][\d]{7}[0-9A-Z])',
			'is_eu' => true,
		],
		'SE' => [
			'iso_3166_2' => 'SE',
			'pattern' => '(SE)?[\d]{12}',
			'is_eu' => true,
		],
		'GB' => [
			'iso_3166_2' => 'GB',
			'pattern' => '(GB)?([\d]{7}([0-8][\d]|9[0-6])([\d]{3})?|GD[0-4][\d]{2}|HA[5-9][\d]{2}|8{4}(([0-4][\d]{2}GD)|([5-9][\d]{2}HA)))',
			'is_eu' => true,
		],
		'NO' => [
			'iso_3166_2' => 'NO',
			'pattern' => '(NO)?[\d]{1,9}',
			'is_eu' => true,
		],
		'KR' => [
			'iso_3166_2' => 'KR',
			'pattern' => '(KR)?[\d]{10}',
			'is_eu' => false,
		],
		'ZA' => [
			'iso_3166_2' => 'ZA',
			'pattern' => '(ZA)?[4|3][\d]{9,10}',
			'is_eu' => false,
		],
		'NZ' => [
			'iso_3166_2' => 'NZ',
			'pattern' => '(NZ)?[\d]{8,9}',
			'is_eu' => false,
		],
		'CH' => [
			'iso_3166_2' => 'CH',
			'pattern' => '(CHE)?[\d]{9}(MWST|TVA|IVA)',
			'is_eu' => false,
		],
		'IS' => [
			'iso_3166_2' => 'IS',
			'pattern' => '(IS)?[\d]{5,7}',
			'is_eu' => false,
		],
	];

	/**
	 * @param string $isoCode
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public static function getVatCodePattern($isoCode)
	{
		static::validateCountry($isoCode);
		return static::$taxableCountries[$isoCode]['pattern'];
	}

	/**
	 * @param string $isoCode
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public static function isEU($isoCode)
	{
		static::validateCountry($isoCode);
		return static::$taxableCountries[$isoCode]['is_eu'];
	}

	/**
	 * @param string $isoCode
	 * @throws InvalidArgumentException
	 */
	private static function validateCountry($isoCode)
	{
		if (!static::isTaxable($isoCode))
		{
			throw new InvalidArgumentException('Country is not taxable.');
		}
	}

	/**
	 * @param string $isoCode
	 * @return bool
	 */
	public static function isTaxable($isoCode)
	{
		return isset(static::$taxableCountries[$isoCode]);
	}
}