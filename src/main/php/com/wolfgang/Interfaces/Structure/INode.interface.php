<?php

namespace Wolfgang\Interfaces\Structure;

interface INode {
	
	/**
	 *
	 * @return string
	 */
	public function getId ( ): string;
	
	/**
	 *
	 * @return string|int|float|double
	 */
	public function getName ( );
}
