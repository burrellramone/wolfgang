<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Exceptions\Exception as CoreException;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class CacheSessionHandler extends Component implements ISessionHandler {

	/**
	 */
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * @param string $session_id
	 */
	public function destroy ( $session_id ) {

	}

	/**
	 *
	 * @param string $session_id
	 */
	public function read ( $session_id ) {

	}

	/**
	 *
	 * @param string $session_id
	 * @param string $session_data
	 * @throws CoreException
	 * @return bool
	 */
	public function write ( $session_id, $session_data ) {

	}

	/**
	 * 
	 */
	public function create_sid ( ) {

	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \SessionHandlerInterface::gc()
	 */
	public function gc ( $maxlifetime ) {
		return true;
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \SessionHandlerInterface::open()
	 */
	public function open ( $save_path, $session_name ) {
		return true;
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \SessionHandlerInterface::close()
	 */
	public function close ( ) {
		return true;
	}
}