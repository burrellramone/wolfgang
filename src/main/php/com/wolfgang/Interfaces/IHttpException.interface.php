<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IHttpException {
    public function getHttpCode ( ): int;

	public function getHTTPStatus ( ): string;
}