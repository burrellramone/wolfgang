<?php

namespace Wolfgang\Interfaces\Application;

use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Dispatching\Dispatcher;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Interfaces\Session\ISession;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IApplication {
	
	/**
	 *
	 * @var string
	 */
	const KIND_CLI = 'cli';
	
	/**
	 *
	 * @var string
	 */
	const KIND_API = 'api';
	
	/**
	 *
	 * @var string
	 */
	const KIND_SITE = 'site';
	
	/**
	 * Gets the name of the application.
	 *
	 * @return string
	 */
	public function getName ( ): string;
	
	/**
	 * Gets the kind of this application. Return value will be one of 'cli', 'api', 'site'
	 *
	 * @return string
	 */
	public function getKind ( ): string;
	
	/**
	 *
	 * @param IRequest $message
	 */
	public function execute ( IRequest $message );
	
	/**
	 * Sends a response from the application.
	 * 
	 * @param string|\Exception|null $message The message to exit the application with
	 */
	public function respond ( $message = null );
	
	/**
	 *
	 * @return IRouter
	 */
	public function getRouter ( ): IRouter;
	
	/**
	 *
	 * @return Dispatcher
	 */
	public function getDispatcher ( ): Dispatcher;
	
	/**
	 *
	 * @param IRoute $route
	 */
	public function addRoute ( IRoute $route );
	
	/**
	 *
	 * @return IRequest
	 */
	public function getRequest ( ): IRequest;
	
	/**
	 *
	 * @return IResponse
	 */
	public function getResponse ( ): IResponse;
	
	/**
	 *
	 * @return IContext
	 */
	public function getContext ( ): IContext;

	/**
	 * 
	 * Gets the session of the application
	 * 
	 * @return ISession The session of the application
	 */
	public function getSession(): ISession;
	
	/**
	 * If the current application being run is of the kind 'api' or 'site' then an HTTP 'Location'
	 * header for a particular URI will be sent and execution will end. If however the application
	 * being run is of the kind 'cli' then execution will be redirected to another controller and
	 * action within the cli application
	 *
	 * @param IUri|string $uri
	 */
	public function redirect ( IUri|string $uri ): void;

	/**
	 * @return IApplication
	 */
	public static function getInstance ( ): IApplication;
}
