<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IModel {
	
	/**
	 *
	 * @return string|int|null
	 */
	public function getId ( ):string|int|null;
	
	/**
	 *
	 * @return IModel
	 */
	public function save ( ): IModel;
	
	/**
	 *
	 * @return string
	 */
	public function getModelType ( ): string;
}
