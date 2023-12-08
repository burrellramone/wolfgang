<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IDeltaChange;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
class DeltaChange extends Component implements IDeltaChange {
	/**
	 *
	 * @var string
	 */
	private $property;

	/**
	 *
	 * @var mixed
	 */
	private $old_value;

	/**
	 *
	 * @var mixed
	 */
	private $new_value;

	/**
	 *
	 * @param string $property
	 * @param mixed $old_value
	 * @param mixed $new_value
	 */
	public function __construct ( string $property, $old_value, $new_value ) {
		parent::__construct();

		$this->property = $property;
		$this->old_value = $old_value;
		$this->new_value = $new_value;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IDeltaChange::getProperty()
	 */
	public function getProperty ( ): string {
		return $this->property;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IDeltaChange::getOldValue()
	 */
	public function getOldValue ( ) {
		return $this->old_value;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IDeltaChange::getNewValue()
	 */
	public function getNewValue ( ) {
		return $this->new_value;
	}
}
