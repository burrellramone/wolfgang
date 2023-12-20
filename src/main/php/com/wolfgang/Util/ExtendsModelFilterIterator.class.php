<?php

namespace Wolfgang\Util;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class ExtendsModelFilterIterator extends \RecursiveFilterIterator {
	
	/**
	 *
	 * @var \ReflectionClass
	 */
	private $subject_class;
	
	/**
	 *
	 * @param \ReflectionClass $subject_class
	 */
	public function setSubjectClass ( \ReflectionClass $subject_class ) {
		$this->subject_class = $subject_class;
	}
	
	public function accept ( ):bool {
		if ( $this->current()->isDir() ) {
			return false;
		}
		
		$file = $this->current();
		$filename = $file->getFilename();
		
		if ( ! preg_match( "/\.php$/", $filename ) ) {
			return false;
		}
		
		$class_name = 'Model\\' . preg_replace( "/\.(.*)$/", '', $filename );
		
		if ( ! class_exists( $class_name, true ) ) {
			require_once ($file);
		}
		
		$class_reflector = new \ReflectionClass( $class_name );
		
		// @TODO: An abstract can extend another class. But for the purpose of what we use this
		// class for we will so No, an abstract class cannot extend another class
		if ( $class_reflector->isAbstract() ) {
			return false;
		}
		
		if ( $class_reflector->getName() == $this->subject_class->getName() ) {
			return false;
		}
		
		$parent_class = $class_reflector->getParentClass();
		
		while ( $parent_class ) {
			if ( $parent_class->getName() == $this->subject_class->getName() ) {
				return true;
			}
			
			$parent_class = $parent_class->getParentClass();
		}
		
		return false;
	}
}