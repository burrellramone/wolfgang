<?php
namespace Application;

//Wolfgang
use Wolfgang\Application\Api as WolfgangApiApplication;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\IHttpException;
use Wolfgang\Encoding\JSON;
use Wolfgang\Interfaces\IMarshallable;

//
use Traits\TApplication;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
final class Api extends WolfgangApiApplication {
    use TApplication;
    
    public function __construct( IContext $context ){
        parent::__construct($context);
        
        $this->addEventListeners();
    }

    public static function showErrorResponse($e, $response = null){
		$status_line = "Error";
        $error = null;

		if($response){
			$status_line = $response->getStatusLine();
		} else if ($e instanceof IHttpException) {
			$status_line = $e->getStatusLine();
		}

        if($e instanceof IMarshallable){
            $error = $e->marshall();
        } else {
            $error = $e->getTraceAsString();
        }

        header( $status_line );

		echo JSON::encode([
            "success" => false,
            "message" => $status_line,
            "error" => $error,
        ]);
        exit;
	}
}
