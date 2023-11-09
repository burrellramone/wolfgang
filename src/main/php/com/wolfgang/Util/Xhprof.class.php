<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Application\Application;
use Wolfgang\Network\Uri\Uri;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Xhprof extends Component implements ISingleton {
	use TSingleton;
	
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	public function header ( ) {
		if ( extension_loaded( 'xhprof' ) ) {
			include_once WOLFGANG_DIRECTORY . 'vendor/lox/xhprof/xhprof_lib/utils/xhprof_lib.php';
			include_once WOLFGANG_DIRECTORY . 'vendor/lox/xhprof/xhprof_lib/utils/xhprof_runs.php';
			xhprof_enable( XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY );
		}
	}
	
	public function footer ( ) {
		if ( extension_loaded( 'xhprof' ) ) {
			$application = Application::getInstance();
			$profiler_namespace = $application->getProfileNamespace();
			$profiler_url = $application->getContext()->getSkinDomain()->getUrl() . '/xhprofile/';
			$xhprof_data = xhprof_disable();
			$xhprof_runs = new \XHProfRuns_Default();
			$run_id = $xhprof_runs->save_run( $xhprof_data, $profiler_namespace );
			
			$profiler_url = $profiler_url . "{$run_id}/{$profiler_namespace}";
			$application->setProfileRunUri( new Uri( $profiler_url ) );
		}
	}
}
