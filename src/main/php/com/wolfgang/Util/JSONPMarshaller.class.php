<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IMarshaller;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Encoding\JSON;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class JSONPMarshaller extends Component implements IMarshaller , ISingleton {

	/**
	 *
	 * @var DataTableMarshaller
	 */
	private static $instance;

	private function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * @return JSONPMarshaller
	 */
	public static function getInstance ( ): JSONPMarshaller {
		if ( ! self::$instance ) {
			self::$instance = new JSONPMarshaller();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\IMarshaller::marshal()
	 */
	public function marshall ( IMarshallable $object ): \stdClass {
		echo $_REQUEST[ 'callback' ] . "(" . JSON::encode( $object->marshal(), JSON_PRETTY_PRINT ) . ")";
		exit();
	}
}
