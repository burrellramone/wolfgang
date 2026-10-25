<?php
namespace Application;

//Wolfgang
use Wolfgang\Application\Cli as WolfgangCliApplication;
use Wolfgang\Interfaces\Application\IContext;

//AirportRuns
use Traits\TApplication;

/**
 * @author Ranone Burrell <ramone@ramoneburrell.com>
 */
final class Cli extends WolfgangCliApplication {
	use TApplication;

    public function __construct( IContext $context ){
        parent::__construct($context);

        $this->addEventListeners();
    }
}