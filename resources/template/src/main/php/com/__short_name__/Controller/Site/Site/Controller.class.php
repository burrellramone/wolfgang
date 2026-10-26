<?php
namespace Controller\Site\Site;


//Wolfgang
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;

use Controller\Site as SiteController;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Controller\Site
 */
abstract class Controller extends SiteController {

	/**
	 *
	 * @param IRequest $request
	 * @param IResponse $response
	 */
	public function __construct ( IRequest $request, IResponse $response ) {
		parent::__construct( $request, $response );
	}
}
