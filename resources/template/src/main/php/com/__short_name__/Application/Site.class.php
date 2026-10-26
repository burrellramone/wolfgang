<?php
namespace Application;

//PHP
use Exception;
use Error;
use ErrorException;
use TypeError;

//Wolfgang
use Wolfgang\Interfaces\IHttpException;
use Wolfgang\Templating\Templater;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Application\Site as WolfgangSiteApplication;
use Wolfgang\Config\App as AppConfig;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Application\Context;
use Wolfgang\Interfaces\Message\HTTP\IResponse as IHttpResponse;
use Wolfgang\Exceptions\Message\HTTP\Exception as HTTPException;

use Traits\TApplication;

/**
 * @author Ranone Burrell <ramone@ramoneburrell.com>
 * @property Response $response
 * @property Templater $tmpl
 */
final class Site extends WolfgangSiteApplication {
	use TApplication;
    
    public function __construct( IContext $context ){
        parent::__construct($context);

		$this->addEventListeners();
    }

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::respond()
	 */
	public function respond ( $message = null) {
		$context = Context::getInstance();
		$response = $this->getResponse();

		if ( $response->isError() ) {
			header( $response->getStatusLine() );

			$this->tmpl->setLayout("Common/layout/error.tmpl");
			
			self::showErrorPage($message);

			exit();
		}
		
		$body = $response->getBody();
		
		$response->setBody($body);
		
		//MUST write and close session here before 'respond' is called since 'respond' will result in output being written
		session_write_close();

		echo $response;
	}

    /**
	 *
	 * @param IRequest $request
	 */
	public function execute ( IRequest $request ) {
		try {

			$e = null;

			parent::execute( $request );

		} catch ( HTTPException $e ) {
			$this->response->setStatusCode( $e->getHttpCode() );
			$this->response->setBody( $e->getMessage() );
		} catch ( Exception $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
			$this->response->setBody( $e->getMessage() );
		} catch ( Error $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
			$this->response->setBody( $e->getMessage() );
		} catch ( TypeError $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
			$this->response->setBody( $e->getMessage() );
		} catch ( ErrorException $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
			$this->response->setBody( $e->getMessage() );
		} finally {
			$this->respond($e);
		}
	}

	public static function showErrorPage(Exception|Error|TypeError|ErrorException $e){
		$templater = Templater::getInstance();

		$status_code = 500;
		$status_line = "Error";

		if ($e instanceof IHttpException) {
			$status_code = $e->getHttpCode();
			$status_line = $e->getStatusLine();
		}

		if(!$templater->getLayout()){
			$templater->setLayout("Common/layout/error.tmpl");
		}

		$templater->setTemplate("Common/sections/errors/{$status_code}.tmpl");
		$templater->assign("page_title", $status_line);
		$templater->assign("e", $e);
		$templater->assign("app_config", AppConfig::getAll());
		$templater->display();
		exit;
	}
}