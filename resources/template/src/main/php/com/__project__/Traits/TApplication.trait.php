<?php

namespace Traits;


// Wolfgang
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Application\Context;
use Wolfgang\Util\Iterator\RegexIterator;
use Wolfgang\Interfaces\IIterator;
use Wolfgang\Util\Filesystem;

/**
 * A class representing a trait of an application. All application types, API, CLI, and Site, will
 * use this trait.
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.0.1
 */
trait TApplication {
	
	public static function getInstance(): IApplication {
		if(self::$instance == null) {
			$called_class = get_called_class();
			self::$instance = new $called_class(Context::getInstance());
		}
		
		return self::$instance;
	}
	
	/**
	 * Adds enabled event listeners to the application through the event dispatcher
	 *
	 * @see EventDispatcher::addEventListener() Event dispatcher method that adds listeners
	 * @return int The total number of event listeners that were added
	 */
	protected function addEventListeners(): int {
		$total_enabled_event_listeners = 0;
		$namespaces = array();
		$namespaces[] = 'EventListener/' . Context::getInstance()->getSkin()->getName();
		$namespaces[] = 'EventListener/Common';
		
		$context = $this->getContext();
		
		foreach ( $namespaces as $namespace ) {
			if(!Filesystem::exists(CLASSES_DIRECTORY . $namespace)) {
				continue;
			}
			
			$regex_iterator = RegexIterator::create("/^.+\.class.php$/i", IIterator::TYPE_RECURSIVE_DIRECTORY_ITERATOR, CLASSES_DIRECTORY . $namespace);
			$namespace = str_replace("/", "\\", $namespace);
			$event_dispatcher = $this->getEventDispatcher();
			
			foreach ( $regex_iterator as $object ) {
				$class_name = preg_replace("/\.class\.php/", '', $object->getFileName());
				$class_name = "{$namespace}\\{$class_name}";
				$event_listener = new $class_name($context);
				
				if(!$event_listener->isEnabled()) {
					continue;
				}
				
				$event_dispatcher->addEventListener($event_listener);
				$total_enabled_event_listeners++;
			}
		}
		
		return $total_enabled_event_listeners;
	}
}
