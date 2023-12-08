<?php

namespace Wolfgang\Interfaces\Application;

use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Dispatching\Dispatcher;
use Wolfgang\Interfaces\Network\IUri;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
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
	 * @param IMessage $message
	 * @return IResponse
	 */
	public function execute ( IMessage $message ): IResponse;
	
	/**
	 * Sends a response from the application. Depending under what circumstance this method was
	 * called it will decide wether to return an instance of an a message response,
	 * Wolfgang\Interfaces\Message\IResponse, or wite the response immediately and terminate with an
	 * error code.
	 *
	 * @param string|\Exception|null $message The message to exit the application with
	 * @return IResponse|null An instance of Wolfgang\Interfaces\Message\IResponse if the method was
	 *         called under normal circumstances or nothing on termination if the method was called
	 *         in a __toString magic method
	 */
	public function respond ( $message = null ): ?IResponse;
	
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
	 * If the current application being run is of the kind 'api' or 'site' then an HTTP 'Location'
	 * header for a particular URI will be sent and execution will end. If however the application
	 * being run is of the kind 'cli' then execution will be redirected to another controller and
	 * action within the cli application
	 *
	 * @param IUri $uri
	 */
	public function redirect ( IUri $uri ): void;
}
