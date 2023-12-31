<?php

namespace Wolfgang\Interfaces\Message\HTTP;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IApiRequest extends IRequest {

	/**
	 *
	 * @return bool
	 */
	public function isInternal ( ): bool;

	/**
	 *
	 * @return bool
	 */
	public function isExternal ( ): bool;

	/**
	 * Gets the API key that was sent along with this request as the header X_API_KEY
	 *
	 * @return string|NULL The API key if it was sent with this request, null otherwise
	 */
	public function getApiKey ( ): ?string;
}