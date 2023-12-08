<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Exceptions\Exception as CoreException;
/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DatabaseSessionHandler extends Component implements ISessionHandler {

	/**
	 */
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * @param string $session_id
	 * @return bool
	 */
	public function destroy ( $session_id ): bool {
		return true;
	}

	/**
	 *
	 * @param string $session_id
	 */
	public function read ( $session_id ): string|false {
		return false;
	}

	/**
	 *
	 * @param string $session_id
	 * @param string $session_data
	 * @throws CoreException
	 * @return bool
	 */
	public function write ( $session_id, $session_data ):bool {
		return false;
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
	public function gc ( $maxlifetime ):int|false {
		return true;
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \SessionHandlerInterface::open()
	 */
	public function open ( $save_path, $session_name ):bool {
		return true;
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \SessionHandlerInterface::close()
	 */
	public function close ( ):bool {
		return true;
	}
}