<?php

namespace Wolfgang\Extension;

use Wolfgang\Component as BaseComponent;
use Wolfgang\Interfaces\IExtension;
use Wolfgang\Interfaces\IExtensible;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Extension
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
abstract class Extension extends BaseComponent implements IExtension {
	/**
	 *
	 * @var IExtensible
	 */
	protected $subject;
	
	/**
	 *
	 * @param IExtensible $subject
	 */
	public function __construct ( IExtensible $subject ) {
		parent::__construct();
		
		$this->subject = $subject;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IExtension::getSubject()
	 */
	public function getSubject ( ): IExtensible {
		return $this->subject;
	}
}
