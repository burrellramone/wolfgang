<?php

namespace Wolfgang\Interfaces\Session;

/**
 *
 * @package Wolfgang\Interfaces\Session
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface ISession {

	/**
	 *
	 * @var string
	 */
	const KIND_COOKIE = 'cookie';

	/**
	 *
	 * @var string
	 */
	const KIND_CACHE = 'cache';

	/**
	 *
	 * @var string
	 */
	const KIND_DATABASE = 'database';

	/**
	 *
	 * @var string
	 */
	const KIND_FILE = 'file';

	/**
	 */
	public function close ( );

	/**
	 *
	 * @return ISessionHandler
	 */
	public function getHandler ( ): ISessionHandler;
}