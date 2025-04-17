<?php

namespace Wolfgang\Interfaces\Model;

use Wolfgang\Interfaces\ORM\IQueryBuilder;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.0.1
 */
interface ISearchable extends IModelList {
	/**
	 * @param string $searchValue
	 * @return IQueryBuilder
	 */
	public function search (string $searchValue): IQueryBuilder;

	/**
	 * Gets a list of columns that should be looked at when ISearchable::search() is called
	 * @return array
	 */
	public function getSearchableColumns():array;
}
