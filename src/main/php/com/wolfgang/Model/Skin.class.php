<?php

namespace Wolfgang\Model;

use Wolfgang\Interfaces\Model\ISkin;
use Wolfgang\Traits\Model\TSkin;
use Wolfgang\Date\DateTime;

/**
 *
 * @package Wolfgang\Model
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Skin extends Model implements ISkin {
	use TSkin;

	/**
	 *
	 * @var DateTime
	 */
	public $last_updated;
	/**
	 *
	 * @var DateTime
	 */
	public $create_date;
}
