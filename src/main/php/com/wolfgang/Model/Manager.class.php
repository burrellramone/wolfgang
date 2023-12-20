<?php

namespace Wolfgang\Model;

use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Serialization\Serializer;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Manager extends Component implements ISingleton {
	use TSingleton;

	/**
	 *
	 * @var int
	 */
	const PROTOTYPE_MODEL_INSTANCE_KEY = - 1;

	/**
	 *
	 * @var array
	 */
	protected $models = [ ];

	protected function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * @param string $type
	 * @throws IllegalArgumentException
	 * @return \Wolfgang\Interfaces\Model\IModel|object
	 */
	public function create ( string $type ) {
		if ( ! $type ) {
			throw new IllegalArgumentException( "Model type must be provided" );
		}

		$model = $this->get( $type );

		if ( $model ) {
			return clone $model;
		}

		$reflector = new \ReflectionClass( $type );

		if ( $reflector->isAbstract() ) {

			$recursive_directory_iterator = new \RecursiveDirectoryIterator( MODELS_DIRECTORY );
			$recursive_filter_iterator = new \Wolfgang\Util\ExtendsModelFilterIterator( $recursive_directory_iterator );
			$recursive_filter_iterator->setSubjectClass( $reflector );
			$recursive_iterator_iterator = new \RecursiveIteratorIterator( $recursive_filter_iterator );
			$files = new \RegexIterator( $recursive_iterator_iterator, "/.*\.(php)$/" );
			;
			foreach ( $files as $file ) {
				$filename = $file->getFilename();
				$class_name = 'Model\\' . preg_replace( "/\.(.*)$/", '', $filename );
				$reflector = new \ReflectionClass( $class_name );
				break;
			}
		}

		$model = $reflector->newInstance();

		$this->put( $model );

		return clone $model;
	}

	/**
	 *
	 * @param string
	 * @param string|int $id
	 * @return \Wolfgang\Interfaces\Model\IModel | null
	 */
	public function get ( string $type, $id = self::PROTOTYPE_MODEL_INSTANCE_KEY): ?IModel {
		if ( ! $this->exists( $type, $id ) ) {
			return null;
		}

		return $this->models[ $type ][ $id ][ 'instance' ];
	}

	/**
	 *
	 * @param string $type
	 * @param string $key
	 * @param IModel $model
	 * @throws IllegalArgumentException
	 */
	public function put ( IModel $model ) {
		$type = $model->getModelType();

		if ( empty( $this->models[ $type ] ) ) {
			$this->models[ $type ] = array ();
		}

		$id = $model->getId();

		if ( ! $id ) {
			$id = self::PROTOTYPE_MODEL_INSTANCE_KEY;
		}

		$columns = $model->getTable()->getColumns();

		$state = [ ];

		foreach ( $columns as $column ) {
			$state[ $column->getName() ] = $model->{$column->getName()};
		}

		$state = gzcompress( Serializer::serialize( $state ) );

		$this->models[ $type ][ $id ] = [ 
				'instance' => $model,
				'put_state' => $state
		];
	}

	/**
	 * Replaces a stored instance of a model within this manager with a provided one if it is
	 * currently stored
	 *
	 * @param IModel $model The model instance to use in replacing a possible stored model instance
	 * @return bool True if the model was sucessfully replaced, false otherwise
	 */
	public function replace ( IModel $model ): bool {
		if ( ! $this->exists( $model->getModelType(), $model->getId() ) ) {
			return false;
		}

		$this->put( $model );

		return true;
	}

	/**
	 * Gets a key/property-value array representation of a model as it was initially placed in this
	 * manager
	 *
	 * @param IModel $model
	 * @return array|NULL
	 */
	public function getPutState ( IModel $model ): ?array {
		if ( ! $this->exists( $model->getModelType(), $model->getId() ) ) {
			return null;
		}

		return Serializer::unserialize( gzuncompress( $this->models[ $model->getModelType() ][ $model->getId() ][ 'put_state' ] ) );
	}

	/**
	 * Determines whether or not a model of a specific type and optional id exists within this
	 * manager
	 *
	 * @param string $type The type of the model to check if exists
	 * @param string|int $id The id of the model to check if exists
	 * @throws InvalidArgumentException Throws an instance of InvalidArgumentException if the model
	 *         type is not provided
	 */
	public function exists ( string $type, $id = self::PROTOTYPE_MODEL_INSTANCE_KEY): bool {
		if ( ! $type ) {
			throw new InvalidArgumentException( "Model type must be provided" );
		} else if ( empty( $this->models[ $type ] ) ) {
			return false;
		}

		if ( ! $id ) {
			$id = 0;
		}

		if ( empty( $this->models[ $type ][ $id ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param string $type
	 * @param string|int $id
	 * @return bool
	 */
	public function delete ( $type, $id = self::PROTOTYPE_MODEL_INSTANCE_KEY): bool {
		if ( ! $this->exists( $type, $id ) ) {
			return false;
		}

		unset( $this->models[ $type ][ $id ] );

		return true;
	}
}
