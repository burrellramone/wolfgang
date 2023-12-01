<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IContact;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
class Contact extends Component implements IContact {
	/**
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 *
	 * @param string $name
	 */
	public function __construct ( string $name ) {
		parent::__construct();
		
		$this->setName( $name );
	}
	
	/**
	 *
	 * @param string $name
	 */
	private function setName ( string $name ) {
		$this->name = $name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IContact::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}
}
