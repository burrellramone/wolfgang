<?php

namespace Wolfgang\Interfaces;

use Wolfgang\Interfaces\Model\IModel;

interface ISchema {

	/**
	 *
	 * @param IModel $model
	 * @return IModel
	 */
	public function save ( IModel $model );

	/**
	 *
	 * @param string $type
	 * @param string $id
	 * @return IModel|void|null
	 */
	public function read ( string $type, string $id ): ?IModel;

	/**
	 *
	 * @param IModel $model
	 * @return IModel
	 */
	public function update ( IModel $model ): IModel;

	/**
	 *
	 * @param IModel $model
	 * @return IModel
	 */
	public function delete ( IModel $model ): IModel;

	/**
	 * Gets the name of the schema
	 *
	 * @return string The name of the schema.
	 */
	public function getName ( ): string;
}
