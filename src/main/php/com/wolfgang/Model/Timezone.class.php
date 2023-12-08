<?php

namespace Wolfgang\Model;

use DateTimeZone;
use Wolfgang\Traits\TLabelled;
use Wolfgang\Interfaces\Model\ITimezone as ITimezoneModel;
use Wolfgang\Interfaces\Date\ITimezone;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Timezone extends Model implements ITimezoneModel , ITimezone {
	use TLabelled;
	public $name;
	public $label;
	public $coordinates;
	public $abbr;
	public $dst_abbr;
	public $gmt_offset;
	public $dst_gmt_offset;
	public $country_code;
	public $comments;
	public $notes;
	public $create_date;

	/**
	 *
	 * @access public
	 * @return \DateTimeZone
	 */
	public function toDateTimeZone ( ): DateTimeZone {
		return new DateTimeZone( $this->getLabel() );
	}

	/**
	 *
	 * @todo rewrite this method
	 */
	public function isUTC ( ) {
		return $this->getLabel() === 'Europe/London';
	}
}
