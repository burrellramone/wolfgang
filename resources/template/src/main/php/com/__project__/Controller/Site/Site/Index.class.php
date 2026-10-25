<?php

namespace Controller\Site\Site;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Controller\Site\Controller
 * @uses Wolfgang\Templating\Templater
 * @since Version 0.0.1
 */
final class Index extends Controller {

	protected function init ( ) {
		parent::init();
		
		$this->getAuthenticator()->allow( [ 
				'index' 
		] );
	}
	
	public function index ( ) {
		$this->tmpl->assign( "page_title", __("Home"));
	}
}
