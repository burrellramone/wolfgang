<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IDelta;
use Wolfgang\BaseObject;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Delta extends Component implements IDelta , \Iterator , \Countable {
	
	/**
	 *
	 * @var int
	 */
	private $position = 0;
	
	/**
	 *
	 * @var mixed
	 */
	private $subject;
	
	/**
	 *
	 * @var array
	 */
	protected $affected_properties = [ ];
	
	/**
	 *
	 * @param BaseObject $subject
	 */
	public function __construct ( BaseObject $subject ) {
		$this->setSubject( $subject );
		
		parent::__construct();
	}
	
	/**
	 *
	 * @param BaseObject $subject
	 */
	private function setSubject ( BaseObject $subject ) {
		$this->subject = $subject;
	}
	
	/**
	 *
	 * @return BaseObject
	 */
	public function getSubject ( ): BaseObject {
		return $this->subject;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IDelta::getAffectedProperties()
	 */
	public function getAffectedProperties ( ): array {
		return array_keys( $this->affected_properties );
	}
	
	public function getAffectedProperyNames():array {
		$property_names = array();
		
		foreach($this->affected_properties as $delta_change) {
			$property_names[] = $delta_change->getProperty();
		}
		
		return $property_names;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Countable::count()
	 */
	public function count ( ): int {
		return count( $this->affected_properties );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Iterator::next()
	 */
	public function next ( ):void {
		$this->position ++;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Iterator::valid()
	 */
	public function valid ( ):bool {
		return ! empty( $this->affected_properties[ $this->position ] );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Iterator::current()
	 */
	public function current ( ):mixed {
		return $this->affected_properties[$this->key()];
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Iterator::rewind()
	 */
	public function rewind ( ):void {
		$this->position = 0;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Iterator::key()
	 */
	public function key ( ):int {
		return $this->position;
	}
}
