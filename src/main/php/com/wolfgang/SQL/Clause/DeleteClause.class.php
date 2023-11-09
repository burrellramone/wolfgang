<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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
