# EU/Non EU VAT Number Validation #
  
[![Build Status](https://travis-ci.org/vifer/vat-validation.svg?branch=master)](https://travis-ci.org/vifer/vat-validation)  
  
## About ##

- Validate a VAT number
- EU VAT number validation
    - 2 validation levels
        - Regex validation
        - EU commission VIES service validation *If the service is down only the regex validation will be done
- Non EU VAT number validation (South Korean, South Africa, New Zealand, Switzerland and Iceland)
    - Regex validation

## Usage ##

    $validator = (new ValidatorFactory())->makeValidator($countryCode)
	$validator->setVatNumber($vatNumber)
	$validator->isValid()
	  
    $validator = EUValidator()
    $validator->setCountryCode($countryCode)
    $validator->setVatNumber($vatNumber)
    $validator->isValid()
      
    $validator = NonEUValidator()
    $validator->setCountryCode($countryCode)
    $validator->setVatNumber($vatNumber)
    $validator->isValid()

## Requirements ##

* PHP 5.6 >=
* Soap extension enabled

## Disclaimer ##

Have a look at http://ec.europa.eu/taxation_customs/vies/viesdisc.do to know when/how you're allowed to use this service and his information