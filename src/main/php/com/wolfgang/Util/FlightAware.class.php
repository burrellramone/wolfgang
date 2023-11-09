<?php

namespace Wolfgang\Util;

// PHP
use SoapClient;
use SoapFault;

// Wolfgang
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Model\FlightAwareApiCall;
use Wolfgang\Config\FlightAware as FlightAwareConfig;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class FlightAware extends Component implements ISingleton {
	use TSingleton;
	
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @param array $options
	 * @return void
	 */
	private function recordApiCall ( array $options ): void {
		$flight_aware_api_call = new FlightAwareApiCall();
		$flight_aware_api_call->apply( $options );
		$flight_aware_api_call->save();
	}
	
	/**
	 *
	 * @param array $params
	 * @throws ComponentException
	 * @return array
	 */
	public function FlightInfoEx ( array $params ): array {
		$options = array (
				"trace" => true,
				'exceptions' => 0,
				'login' => FlightAwareConfig::get( 'login' ),
				'password' => FlightAwareConfig::get( 'api_key' )
		);
		
		$client = new SoapClient( 'http://flightxml.flightaware.com/soap/FlightXML2/wsdl', $options );
		
		$params = array (
				"ident" => $params[ 'flight_number' ],
				"howMany" => $params[ 'limit' ],
				"offset" => 0
		);
		
		$result = $client->FlightInfoEx( $params );
		
		if ( $result instanceof SoapFault ) {
			throw new ComponentException( "Error while retrieving flight info for flight '$params[flight_number]', {$result->faultstring}" );
		} else {
			$params[ 'function' ] = 'FlightInfoEx';
			$this->recordApiCall( $params );
			
			return ( array ) $result->FlightInfoExResult->flights;
		}
	}
	
	/**
	 *
	 * @param array $params
	 * @throws ComponentException
	 * @return array
	 */
	public function AirlineFlightInfo ( array $params ): array {
		$client = new \SoapClient( 'http://flightxml.flightaware.com/soap/FlightXML2/wsdl', array (
				"trace" => true,
				'exceptions' => 0,
				'login' => FlightAwareConfig::get( 'login' ),
				'password' => FlightAwareConfig::get( 'api_key' )
		) );
		
		$params = array (
				"faFlightID" => $params[ 'flight_id' ]
		);
		
		$result = $client->AirlineFlightInfo( $params );
		
		if ( $result instanceof \SoapFault ) {
			throw new ComponentException( "Error while retrieving airline flight info for flight id '{$params['faFlightID']}', {$result->faultstring}" );
		} else {
			$params[ 'function' ] = 'AirlineFlightInfo';
			$this->recordApiCall( $params );
			
			return ( array ) $result->AirlineFlightInfoResult;
		}
	}
}
