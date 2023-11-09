<?php

namespace Wolfgang\Interfaces\Controller;

use Wolfgang\Interfaces\IControllerAuthenticator;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 */
interface IController {

	/**
	 *
	 * @var integer
	 */
	const KIND_SITE = 1;

	/**
	 *
	 * @var integer
	 */
	const KIND_API = 2;

	/**
	 *
	 * @var integer
	 */
	const KIND_CLI = 2;

	/**
	 *
	 * @return IRequest
	 */
	public function getRequest ( ): IRequest;

	/**
	 *
	 * @return IResponse
	 */
	public function getResponse ( ): IResponse;

	/**
	 *
	 * @return IControllerAuthenticator
	 */
	public function getAuthenticator ( ): IControllerAuthenticator;
}
