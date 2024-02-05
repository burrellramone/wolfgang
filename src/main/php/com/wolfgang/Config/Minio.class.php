<?php

namespace Wolfgang\Config;

use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Minio extends Config {
    
    /**
     * @throws Exception
     * @return array
     */
    public static function getAvailableTenant(){
        $tenant = null;
        $config = self::getAll();
        $tenants = $config['tenants'];
        
        shuffle($tenants);

        foreach( $tenants as $t){
            if($t['available']){
                $tenant = $t;
            }
        }
    
        if ( ! $tenant ) {
            throw new Exception( "Could not find available Minio tenant" );
        }

        return $tenant;
    }
}