<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @package Wolfgang\Interfaces\Model
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IModelList {

	/**
	 * Gets an array of ids for all the objects that are within this list.
	 *
	 * @return array The ids of the objects that are within this list.
	 */
	public function getIds ( ): array;

	/**
	 * The total number of records / rows that were matched from the underlying query used to select
	 * items for this list
	 *
	 * @return int The total number of items that were matched
	 */
	public function getTotalMatches ( ): int;

	/**
	 */
	public function filter ( $field, $value );

	/**
	 * Calls the method 'delete' on all objects / models that are currently within this set
	 */
	public function delete ( );
}
