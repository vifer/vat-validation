<?php

namespace Vifer\Tests;

use PHPUnit_Framework_TestCase;
use Vifer\VatValidation\Validator;

class ValidatorTest extends PHPUnit_Framework_TestCase {

	public function testSanitiseVatNumber()
	{
		for ($i = 0; $i < 256; $i++)
		{
			$vatCode = chr($i);
			$sanitised = Validator::sanitiseVatNumber($vatCode);

			// Assert that 0-9 and A-Z are not stripped. These are passed through unchanged.
			if (($i >= ord('0') && $i <= ord('9')) || ($i >= ord('A') && $i <= ord('Z')))
			{
				$this->assertEquals($vatCode, $sanitised);
			}
			// Assert that a-z are not stripped but are changed to their uppercase versions.
			elseif ($i >= ord('a') && $i <= ord('z'))
			{
				$this->assertEquals(strtoupper($vatCode), $sanitised);
			}
			// Assert that any other characters are considered invalid and are therefore stripped.
			else
			{
				$this->assertEquals('', $sanitised);
			}
		}
	}
}
