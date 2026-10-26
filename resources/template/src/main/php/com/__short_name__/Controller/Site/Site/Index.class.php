<?php

namespace Controller\Site\Site;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Controller\Site\Controller
 * @uses Wolfgang\Templating\Templater
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
