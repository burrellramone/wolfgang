<?php

namespace Wolfgang\Model;

use Wolfgang\Interfaces\Model\IWhiteLabel;
use Wolfgang\Interfaces\IExtensible;
use Wolfgang\Interfaces\IExtension;
use Wolfgang\Extension\Model\WhiteLabel as WhiteLabelExtension;
use Wolfgang\Traits\TExtensible;

/**
 *
 * @package Wolfgang\Model
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class WhiteLabel extends Model implements IWhiteLabel , IExtensible {
	use TExtensible;
	public $name;
	public $business_number;
	public $tax_number;
	public $tax_percentage;
	public $address_id;
	public $subscription_application_fee_percentage;
	public $sm_logo_id;
	public $trial_days;
	public $bucket;
	public $create_date;

	/**
	 *
	 * @var WhiteLabelExtension
	 */
	private $extension;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IExtensible::getExtension()
	 */
	public function getExtension ( ): IExtension {
		if ( ! $this->extension ) {
			$this->extension = new WhiteLabelExtension( $this );
		}
		return $this->extension;
	}
}
