<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @package Wolfgang\Interfaces\Model
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IModel {
	
	/**
	 *
	 * @return string|null
	 */
	public function getId ( );
	
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
