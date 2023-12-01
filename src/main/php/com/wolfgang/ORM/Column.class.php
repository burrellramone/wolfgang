<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\ORM\IColumn;
use Wolfgang\Interfaces\ORM\ITable;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
abstract class Column extends Component implements IColumn {
	
	/**
	 * The name of the column
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 *
	 * @var string
	 */
	protected $qualified_name;
	
	/**
	 * The name of the table this column belongs to
	 *
	 * @var ITable
	 */
	protected $table;
	
	/**
	 * The default value for this column if there is any
	 *
	 * @var string|int|double
	 */
	protected $default_value;
	
	/**
	 * The name of the database this column is from
	 *
	 * @var string
	 */
	protected $db;
	
	/**
	 * The type of this column
	 *
	 * @var int
	 */
	protected $type;
	
	/**
	 *
	 * @param \stdClass $field_definition
	 */
	public function __construct ( ITable $table, $column ) {
		foreach ( $column as $property => $value ) {
			if ( property_exists( $this, $property ) ) {
				if ( $property == 'table' ) {
					continue;
				}
				
				$this->{$property} = $value;
			}
		}
		
		parent::__construct();
		
		$this->setTable( $table );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getQualifiedName()
	 */
	public function getQualifiedName ( ): string {
		return $this->qualified_name;
	}
	
	/**
	 *
	 * @param ITable $table
	 */
	private function setTable ( ITable $table ) {
		$this->table = $table;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getTable()
	 */
	public function getTable ( ): ITable {
		return $this->table;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getDefaultValue()
	 */
	public function getDefaultValue ( ) {
		return $this->default_value;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getType()
	 */
	public function getType ( ): int {
		return $this->type;
	}
}
