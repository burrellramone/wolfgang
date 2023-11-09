<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\IMarshallable;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Util
 * @since Version 1.0.0
 */
final class LatLng extends Component implements IMarshallable {

	/**
	 *
	 * @var double|float
	 */
	private $lat = 0.0;

	/**
	 *
	 * @var double|float
	 */
	private $lng = 0.0;

	/**
	 *
	 * @param double|float $lat
	 * @param double|float $lng
	 */
	public function __construct ( $lat = NULL, $lng = NULL) {
		parent::__construct();

		if ( ! empty( $lat ) ) {
			$this->setLat( $lat );
		}

		if ( ! empty( $lng ) ) {
			$this->setLong( $lng );
		}
	}

	/**
	 *
	 * @param double|float $lat
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 */
	public function setLat ( float $lat ) {
		if ( ! $lat ) {
			throw new IllegalArgumentException( "Latitude must be provided" );
		} else if ( ! is_float( $lat ) ) {
			throw new InvalidArgumentException( "Latitude must be a float value" );
		}

		$this->lat = $lat;
	}

	/**
	 *
	 * @return double|float
	 */
	public function getLng ( ): float {
		return $this->lng;
	}

	/**
	 *
	 * @param double|float $lng
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 */
	public function setLong ( float $lng ) {
		if ( ! $lng ) {
			throw new IllegalArgumentException( "Longitude must be provided" );
		} else if ( ! is_float( $lng ) ) {
			throw new InvalidArgumentException( "Longitude must be a float value" );
		}

		$this->lng = $lng;
	}

	/**
	 *
	 * @return double|float
	 */
	public function getLat ( ): float {
		return $this->lat;
	}

	public function __toString ( ) {
		return $this->getLat() . ',' . $this->getLng();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IMarshallable::marshall()
	 */
	public function marshall ( ): array {
		return [ 
				'lat' => $this->lat,
				'lng' => $this->lng
		];
	}
}
