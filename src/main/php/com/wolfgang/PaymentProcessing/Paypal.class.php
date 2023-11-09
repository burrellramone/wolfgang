<?php

namespace Wolfgang\PaymentProcessing;

// use Wolfgang\Util\Curl;
// use Wolfgang\Encoding\JSON;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\PaymentProcessing
 * @since Version 1.0.0
 */
final class Paypal extends Component implements ISingleton {
	use TSingleton;
	
	public function getAccessToken ( ) {
		// $curl = Curl::getInstance();
		// $response = JSON::decode( $curl->get( $site->getPaypalTokenRequestURL(), array (
		// "headers" => array (
		// "Accept: application/json",
		// "Accept-Language: en_US"
		// )
		// ) ) );
	}
}
