<?php

namespace Wolfgang\Date;

//PHP
use DateTime as PHPDateTime;
use DateInterval;
use DateTimeZone;

//Wolfgang
use Wolfgang\Interfaces\Model\ITimezone;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Model\Timezone;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
final class DateTime extends \DateTime {
	const DEFAULT_FORMAT = 'Y-m-d H:i';

	/**
	 *
	 * @param string $time
	 * @param \DateTimeZone $timezone
	 */
	public function __construct ( string $time = "now", ITimezone $timezone = null) {
		if ( !isset($time) || strtotime( $time ) < 0 ) {
			throw new InvalidArgumentException( "Invalid time provided" );
		}

		$date_timezone = $timezone ? $timezone->toDateTimezone() : null;

		parent::__construct( $time, $date_timezone );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see DateTime::add()
	 */
	public function add ( DateInterval $interval ): PHPDateTime {
		return parent::add( new DateInterval( $interval ) );
	}

	/**
	 *
	 * @return string
	 */
	public function getDateTime ( $format = 'Y-m-d H:i:s') {
		return $this->format( $format );
	}

	/**
	 *
	 * @return string
	 */
	public function getDate ( $format = 'Y-m-d') {
		return $this->format( $format );
	}

	/**
	 *
	 * @return string
	 */
	public function getTime ( $format = 'H:i:s') {
		return $this->format( $format );
	}

	/**
	 * Gets the full name of the day for this Wolfgang\Date\DateTime instance
	 *
	 * @return string
	 */
	public function getDayName ( ) {
		return $this->format( "l" );
	}

	public static function getCurrentTimestamp ( ) {
		return date( "Y-m-d H:i:s", time() );
	}

	public function tomorrow ( ) {
		$tomorrow = new DateTime( $this->getDateTime( "Y-m-d 00:00:00" ), $this->getTimezone()->toDateTimeZone() );
		return $tomorrow->add( new \DateInterval( 'P1D' ) );
	}

	public static function startOfWeek ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( "last sunday midnight" ) ), $timezone );
	}

	public static function endOfWeek ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( "next sunday midnight - 1 second" ) ), $timezone );
	}

	public static function startOfMonth ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( 'first day of this month' ) ), $timezone );
	}

	public static function endOfMonth ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( 'last day of this month - 1 second' ) ), $timezone );
	}

	public static function todayMidnight ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( 'today midnight' ) ), $timezone );
	}

	public static function beforeMidnightTomorrow ( ITimezone $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( 'tomorrow - 1 second' ) ), $timezone );
	}

	/**
	 * Gets an instance of the class Wolfgang\Date\DateTime by a provided str time.
	 *
	 * @param string $str_datetime
	 * @param string $timezone
	 * @return \Wolfgang\Date\DateTime
	 */
	public static function strDateTime ( $str_datetime, $timezone = NULL) {
		return new DateTime( date( "Y-m-d H:i:s", strtotime( $str_datetime ) ), $timezone );
	}

	/**
	 *
	 * @param string $datetime
	 * @param DateTime $utc_datetime
	 * @param DateTime $local_datetime
	 * @param string $timezone_label
	 * @param bool $is_utc
	 */
	public static function getUTCAndLocalDatetimes ( string $datetime, DateTime &$utc_datetime, DateTime &$local_datetime, string $timezone_label, bool $is_utc = FALSE) {
		if ( $is_utc ) {
			$utc_datetime = new DateTime( $datetime, new \DateTimeZone( "Europe/London" ) );
			$local_datetime = new DateTime( $datetime, new \DateTimeZone( "Europe/London" ) );
			$local_datetime->setTimezone( new \DateTimeZone( $timezone_label ) );
		} else {
			$utc_datetime = $local_datetime = new DateTime( $datetime, new \DateTimeZone( $timezone_label ) );
			$utc_datetime->setTimezone( new \DateTimeZone( "Europe/London" ) );
		}
	}

	/**
	 *
	 * @param DateTime $time
	 * @return string
	 */
	public function getElapsedTime ( DateTime $datetime = null): string {
		$elapsedTime = '';
		$time = 0;

		if ( ! $datetime ) {
			$datetime = new DateTime();
		}

		$time = time() - $time;
		// To get the time since that moment

		$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
		);

		foreach ( $tokens as $unit => $text ) {
			if ( $time < $unit )
				continue;
			$numberOfUnits = floor( $time / $unit );
			$elapsedTime = $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . " ago";
		}

		return $elapsedTime;
	}

	/**
	 *
	 */
	public function getModelTimezone ( ):Timezone {
		$timezone_name = parent::getTimezone()->getName();
		return Timezone::findByLabel( $timezone_name );
	}

	/**
	 *
	 * @return string
	 */
	public function __toString ( ) {
		return $this->getDateTime();
	}
}