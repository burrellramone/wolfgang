<?php
/**
 * Index file where all non-api calls are sent for processing.
 * 
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.0.1
 *
 */

 //Wolfgang
 use Wolfgang\Message\HTTP\Request;

 use Application\Site;

try {
    $e = null;
    
    require_once (dirname( __DIR__ ) . '/includes/common.php');
    
    Site::getInstance()->execute( Request::getInstance() );

} catch(Error $e) {
    
} catch(ErrorException $e) {

} catch(Exception $e) {

} finally {
    if($e){
        Site::showErrorPage($e);
    }
}