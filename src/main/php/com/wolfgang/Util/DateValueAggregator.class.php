<?php

namespace Wolfgang\Util;

// PHP
use DatePeriod;
use DateInterval;

// Wolfgang
use Wolfgang\Date\DateTime;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DateValueAggregator extends Component {
	/**
	 *
	 * @var array
	 */
	private $dates = [ ];

	/**
	 *
	 * @var array
	 */
	private $date_values = [ ];

	/**
	 *
	 * @var DateTime
	 */
	private $first_date;

	/**
	 *
	 * @var DateTime
	 */
	private $last_date;

	/**
	 *
	 * @var string
	 */
	private $interval;

	/**
	 *
	 * @var string
	 */
	private $mode;

	/**
	 *
	 * @var int
	 */
	private $round_precision;

	/**
	 *
	 * @var array
	 */
	private static $modes = array (
			'sum',
			'average'
	);

	/**
	 *
	 * @param DateTime $from
	 * @param DateTime $to
	 * @param string $interval
	 * @param string $mode
	 * @param number $round_precision
	 * @throws Exception
	 */
	public function __construct ( DateTime $from, DateTime $to, $interval = INTERVAL_DAY, $mode = 'sum', $round_precision = 2) {
		parent::__construct();

		if ( ! in_array( $interval, array (
				INTERVAL_DAY,
				INTERVAL_WEEK,
				INTERVAL_MONTH,
				INTERVAL_YEAR
		) ) ) {
			throw new Exception( 'Unsupported interval' );
		} else if ( ! in_array( $mode, self::$modes ) ) {
			throw new Exception( 'Unsupported mode' );
		} else if ( ! is_int( $round_precision ) ) {
			throw new Exception( 'Invalid rounding precisiond' );
		}

		$this->interval = $interval;
		$this->mode = $mode;
		$this->round_precision = $round_precision;

		$this->first_date = new DateTime( $this->getDatePeriodStartDate( $from ) );
		$this->last_date = new DateTime( date( DATE_FORMAT, strtotime( $this->getNextDate( $to ) . ' - 1 day' ) ) );
		$this->last_date->setTime( 23, 59, 59 );

		$interval_spec = 'P1';

		switch ( $this->interval ) {
			case INTERVAL_DAY :
				$interval_spec .= "D";
				break;
			case INTERVAL_WEEK :
				$interval_spec .= "W";
				break;
			case INTERVAL_MONTH :
				$interval_spec .= "M";
				break;
			case INTERVAL_YEAR :
				$interval_spec .= "Y";
				break;
		}

		$period = new DatePeriod( $this->first_date, new DateInterval( $interval_spec ), $this->last_date );

		foreach ( $period as $date ) {
			$this->dates[] = $date;
			$this->date_values[ $date->format( DATE_FORMAT ) ] = array ();
		}
	}

	/**
	 *
	 * @param string $date
	 * @param float $value
	 */
	public function add ( string $date, float $value ): void {
		if ( isset( $this->date_values[ $this->getDatePeriodStartDate( $date ) ] ) ) {
			$this->date_values[ $this->getDatePeriodStartDate( $date ) ][] = $value;
		}
	}

	/**
	 *
	 * @return DateTime
	 */
	public function getFirstDate ( ): DateTime {
		return $this->first_date;
	}

	/**
	 *
	 * @return DateTime
	 */
	public function getLastDate ( ): DateTime {
		return $this->last_date;
	}

	/**
	 *
	 * @return array
	 */
	public function getDates ( ): array {
		return $this->dates;
	}

	/**
	 *
	 * @return array
	 */
	public function getValues ( ): array {
		$values = array ();

		foreach ( $this->date_values as $date => $vals ) {
			$sum = array_sum( $vals );
			$temp = $sum;

			if ( $this->mode == 'average' && count( $vals ) ) {
				$temp = $temp / count( $vals );
			}

			if ( $this->round_precision ) {
				$temp = round( $temp, $this->round_precision );
			}

			$values[ $date ] = $temp;
		}

		return $values;
	}

	/**
	 *
	 * @param string $date
	 * @throws Exception
	 * @return string
	 */
	public function getDatePeriodStartDate ( string $date ): string {
		if ( $this->interval == INTERVAL_DAY ) {
			return date( DATE_FORMAT, strtotime( $date ) );
		} else if ( $this->interval == INTERVAL_WEEK ) {
			return date( DATE_FORMAT, strtotime( "this week", strtotime( $date ) ) );
		} else if ( $this->interval == INTERVAL_MONTH ) {
			return date( DATE_FORMAT, strtotime( "first day of this month", strtotime( $date ) ) );
		} else if ( $this->interval == INTERVAL_YEAR ) {
			return date( DATE_FORMAT, strtotime( "first day of january", strtotime( $date ) ) );
		} else {
			throw new Exception( "Unsupported interval" );
		}
	}

	/**
	 *
	 * @param string $date
	 * @throws Exception
	 * @return string
	 */
	public function getNextDate ( string $date ): string {
		$period_start_date = $this->getDatePeriodStartDate( $date );

		if ( $this->interval == INTERVAL_DAY ) {
			return date( DATE_FORMAT, strtotime( $period_start_date . ' + 1 days' ) );
		} else if ( $this->interval == 'week' ) {
			return date( DATE_FORMAT, strtotime( $period_start_date . ' + 7 days' ) );
		} else if ( $this->interval == 'month' ) {
			return date( DATE_FORMAT, strtotime( $period_start_date . ' + 1 months' ) );
		} else if ( $this->interval == INTERVAL_YEAR ) {
			return date( DATE_FORMAT, strtotime( $period_start_date . ' + 1 years' ) );
		} else {
			throw new Exception( "Unsupported interval" );
		}
	}
}
