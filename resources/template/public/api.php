<?php
 //Wolfgang
 use Wolfgang\Message\HTTP\ApiRequest;

 //
 use Application\Api;

try {
    /**
     * PHP script where all API requests are sent to for processing
     * 
     * @author Ramone Burrell <ramone@ramoneburrell.com>
     * @since Version 1.0.0
     */
    require_once (dirname( __DIR__ ) . '/includes/common.php');

    $application = Api::getInstance();

    $application->execute( ApiRequest::getInstance() );

} catch(Error $e) {
    
} catch(ErrorException $e) {

} catch(Exception $e) {

} finally {
    if($e){
        Api::showErrorResponse($e);
    }
}