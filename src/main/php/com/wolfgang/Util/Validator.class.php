<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Validator extends Component {

	/**
	 *
	 * @see http://php.net/manual/en/function.filter-var.php
	 * @param string $url
	 * @param mixed $options
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public static function isURL ( $url, $options = null): bool {
		if ( ! is_string( $url ) ) {
			return false;
		}

		return filter_var( $url, FILTER_VALIDATE_URL, $options );
	}
	
	/**
	 * 
	 * @param string $phone
	 * @param string $countryISOCode
	 * @return bool
	 */
	public static function validatePhoneNumber(string $phone, string $countryISOCode = COUNTRY_ISO_CA):bool {
	    if (!$phone) {
	        return false;
	    }
	    
	    switch($countryISOCode){
	        //Countries with NN (National (Significant) Number) of 10
	        case COUNTRY_ISO_CA:
	            if (!preg_match("/^(\+?[1-9](\s|-)?)?[0-9]{3}(-|\s)?[0-9]{3}(-|\s)?[0-9]{4}$/", $phone)) {
	                return false;
	            }
	           break;
	           
	        default:
	            throw new InvalidArgumentException("Unrecognized country ISO code '{$countryISOCode}'.");
	    }
	    
	    return true;
	}
}
