<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\Session\ISessionHandler;

/**
 *
 * @package Wolfgang\Session
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class FileSessionHandler extends Component implements ISessionHandler {

	/**
	 */
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::destroy()
	 */
	public function destroy ( $session_id ) {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::read()
	 */
	public function read ( $session_id ) {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::write()
	 */
	public function write ( $session_id, $session_data ) {
	}

	/**
	 */
	public function create_sid ( ) {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::gc()
	 */
	public function gc ( $maxlifetime ) {
		return true;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::open()
	 */
	public function open ( $save_path, $session_name ) {
		return true;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \SessionHandlerInterface::close()
	 */
	public function close ( ) {
		return true;
	}
}