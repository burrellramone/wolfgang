<?php

namespace Wolfgang\Model;

use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\Model\IEncrypted;
use Wolfgang\Traits\Model\TEncrypted;
use Wolfgang\Encoding\Base64;
use Wolfgang\Date\DateTime;
use Wolfgang\Serialization\Serializer;
use Wolfgang\Session\Manager as SessionManager;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Interfaces\Model\IModel
 * @since Version 0.1.0
 */
final class RecordHistory extends Model implements IEncrypted {
	use TEncrypted;
	
	/**
	 *
	 * @var string
	 */
	public $user_id;
	
	/**
	 *
	 * @var string
	 */
	public $table_name;
	
	/**
	 *
	 * @var string
	 */
	public $record_id;
	
	/**
	 *
	 * @var string
	 */
	public $record;

	/**
	 *
	 * @var DateTime
	 */
	protected $datetime_created;
	
	/**
	 *
	 * @var IModel
	 */
	protected $model_instance;
	
	/**
	 *
	 * @param IModel $model_instance
	 */
	public function __construct ( IModel $model_instance = null) {
		parent::__construct();
		
		if ( ! empty( $model_instance ) ) {
			$this->setModelInstance( $model_instance );
		}

		$this->datetime_created = new DateTime();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Model\Model::postInit()
	 */
	protected function postInit ( ): IModel {
		parent::postInit();

		return $this;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Model\Model::save()
	 */
	public function save ( ): IModel {
		if ( ! $this->user_id ) {
			return $this;
		}
		return parent::save();
	}
	
	/**
	 *
	 * @param IModel $model_instance
	 * @return \Wolfgang\Model\RecordHistory
	 */
	private function setModelInstance ( IModel $model_instance ) {
		$user_id = SessionManager::getInstance()->getSession()->get( 'user_id' );
		
		if ( $user_id ) {
			$this->model_instance = $model_instance;
			$this->user_id = $user_id;
			$this->table_name = $this->model_instance->getTable()->getName();
			$this->record_id = $this->model_instance->getId();
			$this->record = Base64::encode( Serializer::serialize( $this->model_instance ) );
		}
		
		return $this;
	}

	/**
	 *
	 *
	 * @see \Interfaces\Model\IModel::getCreateDate()
	 */
	public function getDatetimeCreated ( ): DateTime {
		return $this->datetime_created;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getEncryptedColumns ( ): array {
		return [ 
				'record'
		];
	}
}