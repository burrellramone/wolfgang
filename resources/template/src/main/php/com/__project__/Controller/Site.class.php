<?php
namespace Controller;

// Wolfgang
use Wolfgang\Controller\SiteController;
use Wolfgang\Config\App as AppConfig;
use Wolfgang\Application\Context;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;

use Application\Site as SiteApplication;
use Traits\TController;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Controller\SiteController
 */
abstract class Site extends SiteController {
	use TController;

	/**
	 *
	 * @param IRequest $request
	 * @param IResponse $response
	 */
	public function __construct(IRequest $request, IResponse $response) {
		parent::__construct($request, $response);

		$this->application = SiteApplication::getInstance();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Controller\SiteController::init()
	 */
	protected function init() {
		parent::init();

		$request = $this->getRequest();
		$parameters = $request->getParameters();
		$context = Context::getInstance();

		// Default page title, to be overwritten
		$this->tmpl->assign("page_title", $context->getController());


		// Add white label config to template
		$app_config = AppConfig::getAll();

		$this->tmpl->assign('request', $request);

		$this->tmpl->assign('controller', $context->getController());
		$this->tmpl->assign('action', $context->getAction());
		$this->tmpl->assign("app_version", $app_config['version']);
	}

	/**
	 *
	 * @see \Wolfgang\Application\Application::redirect()
	 */
	public function redirect(IUri|string $uri): void {
		$this->application->redirect($uri);
	}

	/**
	 */
	protected function getApplication() {
		if(!$this->application) {
			$this->application = SiteApplication::getInstance();
		}

		return $this->application;
	}

	public function __destruct() {
		parent::__destruct();
	}
}
