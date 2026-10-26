<?php

namespace Model;

//Wolfgang
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Model\ModelList as WolfgangModelList;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Traits\Model\TModelListMarshallable;

//
use Interfaces\Model\IModelList;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
abstract class ModelList extends WolfgangModelList implements IModelList, IMarshallable {
	use TModelListMarshallable;

	/**
	 * Deletes all the objects within this list
	 *
	 * @param bool $hard_delete Whether or not to hard delete the objects from the data store.
	 * true to hard delete, false otherwise
	 * @return ModelList The instance of this model list 
	 * for method chaining, unless an exception is thrown within the process
	 */
	public function delete (bool $hard_delete = false):ModelList {
		$this->each( function ( IModel $model ) use($hard_delete) {
			if($hard_delete){
				$model->purge();
			} else {
				$model->delete();
			}
		} );
		return $this;
	}
}
