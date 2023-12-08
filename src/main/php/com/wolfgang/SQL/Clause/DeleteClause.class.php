<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DeleteClause extends Clause implements IDeleteClause {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return "DELETE";
	}
}
