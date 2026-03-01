<?php

namespace Wolfgang\Interfaces\Model;

use DateTimeZone;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface ITimezone extends IModel {

    /**
     * Gets the identifier for the timezone. (America/Toronto, America/Winnipeg, America/Vancouver, etc.)
     *
     * @return string
     */
    public function getIdentifier():string;


    /**
     * 
     * @return DateTimeZone
     */
    public function toDateTimeZone():DateTimeZone;
}