<?php

namespace Wolfgang\Auth;

use Wolfgang\Interfaces\IControllerAuthenticator;
use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Component as BaseComponent;
use Wolfgang\Interfaces\Model\IUser;
use Wolfgang\Exceptions\Exception as CoreException;
use Wolfgang\Util\Bots;
use Wolfgang\Exceptions\UnauthorizedException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Application\Application;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Encoding\Base64;

/**
 * Controller authentication component. This class authorizes access to a site controller within the
 * framework
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Controller extends BaseComponent implements IControllerAuthenticator {

	/**
	 *
	 * @var IController
	 */
	protected $controller;

	/**
	 *
	 * @var IUser
	 */
	protected $user;

	/**
	 * An array of actions for this instance's controller that have been allowed public access
	 *
	 * @var array
	 */
	protected $allowed_actions = array ();

	/**
	 * An array of actions for this instance's controller that have been denied public access
	 *
	 * @var array
	 */
	protected $denied_actions = array ();

	/**
	 *
	 * @var boolean
	 */
	protected $allow_all = false;

	/**
	 *
	 * @param IController $controller
	 * @param IUser $user
	 */
	public function __construct ( IController $controller, IUser|null $user = null) {
		parent::__construct();

		$this->setController( $controller );

		if ( $user ) {
			$this->setUser( $user );
		}
	}

	/**
	 *
	 * @return null
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * @param IController $controller
	 */
	public function setController ( IController $controller ): void {
		$this->controller = $controller;
	}

	/**
	 *
	 * @param IUser $user
	 */
	public function setUser ( IUser $user ): void {
		if ( $this->user ) {
			throw new CoreException( "Cannot reset user in controller authenticator" );
		}

		$this->user = $user;
	}

	/**
	 *
	 * @return IUser
	 */
	public function getUser ( ): ?IUser {
		return $this->user;
	}

	/**
	 * Allows public access to a controller's action
	 *
	 * @param string|array $action The action to allow
	 * @return null
	 */
	public function allow ( string|array $action ) {
		if ( is_array( $action ) ) {
			foreach ( $action as $ac ) {
				$this->allow( $ac );
			}
			return;
		}

		if ( $action == '*' ) {
			$this->allow_all = true;
		}

		$this->allowed_actions[] = $action;
	}

	/**
	 * Denies public access to a controllers's action
	 *
	 * @param string $action The action to deny
	 * @return null
	 */
	public function deny ( $action ) {
		if ( is_array( $action ) ) {
			foreach ( $action as $ac ) {
				$this->allow( $ac );
			}
			return;
		}

		$this->denied_actions[] = $action;
	}

	/**
	 * Determines if an action is publicly accessible by this authenticator's controller.
	 *
	 * @param string $action
	 * @return boolean TRUE if the action is publicly accessible, FALSE otherwise
	 */
	public function isAllowed ( $action ) {
		if ( $this->allowsAll() ) {
			return true;
		}

		return in_array( $action, $this->allowed_actions );
	}

	/**
	 *
	 * @return boolean
	 */
	public function allowsAll ( ) {
		return $this->allow_all;
	}

	/**
	 *
	 * @return void
	 */
	public function allowAll ( ): void {
		$this->allow_all = true;
	}

	/**
	 *
	 * @param IRequest $request
	 * @param IRoute $route
	 * @throws InvalidArgumentException
	 * @throws UnauthorizedException
	 * @return bool
	 */
	public function authenticate ( IRequest $request, IRoute $route ): bool {
		if ( ! ($request instanceof IHttpRequest) ) {
			throw new InvalidArgumentException( "Request must implement Wolfgang\Interfaces\Message\HTTP\IRequest" );
		}

		if ( ! $this->allowsAll() && ! $this->isAllowed( $route->getAction() ) && empty( $this->getUser() ) ) {
			$application = Application::getInstance();
			$application->addError( "Not authorized to access '{$request->getUri()}'" );
			$application->redirect( new Uri( "/account/login/" . Base64::encode( $request->getUri() ) ) );
		}

		if ( Bots::isBot() && ! Bots::isAllowedBot() ) {
			throw new UnauthorizedException();
		}

		return true;
	}
}
