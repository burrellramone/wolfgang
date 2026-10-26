<?php
namespace Extension;

use Wolfgang\Extension\Extension as ComponentExtension;
use Wolfgang\Interfaces\IExtensible;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
abstract class Extension extends ComponentExtension {

	/**
	 *
	 * @param IExtensible $subject
	 */
	public function __construct(IExtensible $subject) {
		parent::__construct($subject);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::__call()
	 */
	public function __call(string $method, array $arguments) {
		$subject = $this->getSubject();

		if(method_exists($subject, $method)) {
			//@formatter:off
			return call_user_func_array(array($subject, $method), $arguments);
			// @formatter:on
		}

		return parent::__callStatic($method, $arguments);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::__get()
	 */
	public function __get(string $name) {
		$subject = $this->getSubject();

		if(property_exists($subject, $name)) {
			return $subject->{$name};
		}

		return parent::__get($name);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::__get()
	 */
	public function __set($name, $value) {
		$subject = $this->getSubject();

		if(property_exists($subject, $name)) {
			$subject->{$name} = $value;
		}
	}
}
