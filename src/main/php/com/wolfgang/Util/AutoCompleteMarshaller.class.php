<?php

namespace Wolfgang\Util;

use \stdClass;

//Wolfgang
use Wolfgang\Interfaces\IMarshaller;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TMarshaller;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class AutoCompleteMarshaller extends Component implements IMarshaller , ISingleton {
	use TMarshaller;

	private static $autocomplete_fields = [
		'label' => true,
		'value' => true,
		'id' => true
	];

	/**
	 *
	 * @var AutoCompleteMarshaller
	 */
	private static $instance;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * @return AutoCompleteMarshaller
	 */
	public static function getInstance ( ): AutoCompleteMarshaller {
		if ( ! self::$instance ) {
			self::$instance = new AutoCompleteMarshaller();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\IMarshaller::marshall()
	 */
	public function marshall ( IMarshallable $object ): stdClass|array {
		$data = self::recursiveMarshall( $object );

		foreach($data as &$item){
			foreach($item as $field => $value){
				if(!isset(self::$autocomplete_fields[$field])){
					unset($item[$field]);
				}
			}
		}

		return $data;
	}
}
