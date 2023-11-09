<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Statement\DML\IDMLStatement;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\ORM\ITableRelationship;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\SQL\Join;
use Wolfgang\ORM\Schema;
use Wolfgang\Application\Application;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\SQL\Clause
 * @since Version 1.0.0
 */
final class FromClause extends Clause implements IFromClause {
	
	/**
	 * A set of tables which are referenced in this from clause. This includes the table being
	 * directly selected/deleted from as well as those being indirectly selected/deleted from using
	 * joins
	 *
	 * @var \ArrayObject
	 */
	protected $table_references;
	
	/**
	 * A list of the names of the tables which are referenced tables within this from clause.
	 *
	 * @see FromClause::$table_references
	 * @var array
	 */
	protected $referenced_table_names = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected $tables_to_join = [ ];
	
	/**
	 * A set of joins for the referenced tables within this from clause
	 *
	 * @var \ArrayObject
	 */
	protected $joins;
	
	/**
	 *
	 * @param IStatement $statement
	 * @param ITable $table
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IStatement $statement, ITable $table ) {
		if ( ! ($statement instanceof IDMLStatement) ) {
			throw new InvalidArgumentException( "Statement is not an instance of Wolfgang\Interfaces\Statement\DML\IDMLStatement" );
		}
		
		$this->table_references = new \ArrayObject( [ ], \ArrayObject::ARRAY_AS_PROPS );
		
		$this->addReferencedTable( $table );
		
		parent::__construct( $statement );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Clause\Clause::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->joins = new \ArrayObject();
	}
	
	/**
	 * Adds a table which is to be joined as a table reference in this from clasuse.
	 *
	 * @param ITable $table
	 * @param string $type
	 * @param string $alias The alias the table should be given in joining it
	 * @see FromClause::__toString
	 */
	public function joinTable ( ITable $table, $type = IFromClause::JOIN_TYPE_INNER, $alias = null) {
		$this->tables_to_join[ $table->getQualifiedName() ] = [ 
				'type' => $type,
				'alias' => $alias,
				'table' => $table,
				'related_tables' => [ ]
		];
	}
	
	private function joinTables ( ): void {
		if ( empty( $this->tables_to_join ) ) {
			return;
		}
		
		// Check to see if one of the tables to be joined have a relationship with of the referenced
		// tables
		foreach ( $this->tables_to_join as $table_name => $table_to_join ) {
			foreach ( $this->getTableReferences() as $table_reference ) {
				$relationship = $table_to_join[ 'table' ]->getRelationship( $table_reference );
				
				if ( $relationship ) {
					$this->createJoin( $relationship, $table_to_join[ 'type' ], $table_to_join[ 'alias' ] );
					unset( $this->tables_to_join[ $table_name ] );
					reset( $this->tables_to_join );
					break;
				}
			}
		}
		
		if ( empty( $this->tables_to_join ) ) {
			return;
		}
		
		// Tables that are left to be joined
		foreach ( $this->tables_to_join as $key => &$table_to_join_array ) {
			$table_to_join_array[ 'related_tables' ] = Schema::getRelatedTables( $table_to_join_array[ 'table' ] );
		}
		
		// Tables which are already talble references / referenced tables in this from clause
		$referenced_tables = $this->getTableReferences()->getArrayCopy();
		foreach ( $referenced_tables as $key => $referenced_table ) {
			$referenced_tables[ $referenced_table->getQualifiedName() ] = [ 
					'table' => $referenced_table,
					'related_tables' => Schema::getRelatedTables( $referenced_table )
			];
			
			unset( $referenced_tables[ $key ] );
		}
		
		//@formatter:off
		/**
		 *
			Tables To Be Joined Related Tables

			Array
			(
			    [airportruns.vehicle] => Array
			        (
			        	[table] => ITable
			        	
			           	[related_tables] => Array
		                (
		                    [0] => Wolfgang\Proxy\ORM\Table Object
		                        (
		                            [schema_dsn_name:protected] => airportruns
		                            [name:protected] => white_label
		                            [qualified_name:protected] => airportruns.white_label
		                            [hashCode:Wolfgang\BaseObject:private] => 2244
		                            [subject:protected] => 
		                        )
		
		                    [1] => Wolfgang\Proxy\ORM\Table Object
		                        (
		                            [schema_dsn_name:protected] => airportruns
		                            [name:protected] => reservation_details
		                            [qualified_name:protected] => airportruns.reservation_details
		                            [hashCode:Wolfgang\BaseObject:private] => 2245
		                            [subject:protected] => 
		                        )
		
		                    [2] => Wolfgang\Proxy\ORM\Table Object
		                        (
		                            [schema_dsn_name:protected] => airportruns
		                            [name:protected] => business_location
		                            [qualified_name:protected] => airportruns.business_location
		                            [hashCode:Wolfgang\BaseObject:private] => 2246
		                            [subject:protected] => 
		                        )
		
		                    [3] => Wolfgang\Proxy\ORM\Table Object
		                        (
		                            [schema_dsn_name:protected] => airportruns
		                            [name:protected] => user
		                            [qualified_name:protected] => airportruns.user
		                            [hashCode:Wolfgang\BaseObject:private] => 2247
		                            [subject:protected] => 
		                        )
		
		                    [4] => Wolfgang\Proxy\ORM\Table Object
		                        (
		                            [schema_dsn_name:protected] => airportruns
		                            [name:protected] => airport
		                            [qualified_name:protected] => airportruns.airport
		                            [hashCode:Wolfgang\BaseObject:private] => 2248
		                            [subject:protected] => 
		                        )
		
		                )
			        )
			
			)

			Referenced Tables Related Tables

			Array
			(
			    [airportruns.vehicle] => Array
			        (
			        	[table] => ITable
			        	
			            [related_tables] => Array
			                (
			                    [0] => Wolfgang\Proxy\ORM\Table Object
			                        (
			                            [schema_dsn_name:protected] => airportruns
			                            [name:protected] => vehicle_hourly_rate_settings
			                            [qualified_name:protected] => airportruns.vehicle_hourly_rate_settings
			                            [hashCode:Wolfgang\BaseObject:private] => 2250
			                            [subject:protected] => 
			                        )
			
			                    [1] => Wolfgang\Proxy\ORM\Table Object
			                        (
			                            [schema_dsn_name:protected] => airportruns
			                            [name:protected] => vehicle_settings
			                            [qualified_name:protected] => airportruns.vehicle_settings
			                            [hashCode:Wolfgang\BaseObject:private] => 2251
			                            [subject:protected] => 
			                        )
			
			                    [2] => Wolfgang\Proxy\ORM\Table Object
			                        (
			                            [schema_dsn_name:protected] => airportruns
			                            [name:protected] => vehicle_location
			                            [qualified_name:protected] => airportruns.vehicle_location
			                            [hashCode:Wolfgang\BaseObject:private] => 2252
			                            [subject:protected] => 
			                        )
			
			                    [3] => Wolfgang\Proxy\ORM\Table Object
			                        (
			                            [schema_dsn_name:protected] => airportruns
			                            [name:protected] => vehicle_flat_rate_settings
			                            [qualified_name:protected] => airportruns.vehicle_flat_rate_settings
			                            [hashCode:Wolfgang\BaseObject:private] => 2253
			                            [subject:protected] => 
			                        )
			
			                )
			        )
			
			)
		 */
		// @formatter:on
		// 1. Does one of the tables to be joined have a relationship with one of the referenced
		// tables related tables?
		// If so then join the referenced table related table onto the referenced table then
		// the table to be joined onto the referenced table related table and remove the table to be
		// joined from the set of tables to be joined and into the set of referenced tables related
		// tables
		// 2. Does a table related to the table to be joined have a relationship with one of the
		// referenced tables / table references? Join the table related to the table to be joined
		// onto the referenced table / table reference then join the table to be joined onto the
		// related
		// table and finally remove the table to be joined from the set of tables to be joined and
		// into the set of referenced tables related tables
		// 3.Does one of the tables related to the table to be joined have a relationship with one
		// of the referenced tables related tables? Then join the referenced table related table
		// onto
		// the referenced table then join the table to be joined related table onto the referenced
		// table
		// related table then the table to be joined onto the related table. Finally, remove the
		// table
		// to be joined from the set of tables to be joined and into the set of referenced tables
		// related tables
		
		// 1.
		// foreach ( $tables_to_join_related_tables as $table_to_joined =>
		// $table_to_join_related_tables ) {
		// break;
		// }
		// 2.
		foreach ( $this->tables_to_join as $table_to_join_qualified_name => $table_to_join ) {
			foreach ( $table_to_join[ 'related_tables' ] as $table_to_join_related_table ) {
				foreach ( $referenced_tables as $referenced_table ) {
					$r1 = $table_to_join_related_table->getRelationship( $referenced_table[ 'table' ] );
					
					if ( $r1 ) {
						$r2 = $table_to_join[ 'table' ]->getRelationship( $table_to_join_related_table );
						
						$this->createJoin( $r1 );
						$this->createJoin( $r2, $this->tables_to_join[ $table_to_join_qualified_name ][ 'type' ], $this->tables_to_join[ $table_to_join_qualified_name ][ 'alias' ] );
						
						$removed = $this->tables_to_join[ $table_to_join_qualified_name ];
						$referenced_tables = array_merge( $referenced_tables, $removed );
						
						unset( $this->tables_to_join[ $table_to_join_qualified_name ] );
						reset( $this->tables_to_join );
						break 2;
					}
				}
			}
		}
		
		// 3.
		foreach ( $this->tables_to_join as $table_to_join_qualified_name => $table_to_join ) {
			foreach ( $table_to_join[ 'related_tables' ] as $table_to_join_related_table ) {
				foreach ( $referenced_tables as $referenced_table ) {
					foreach ( $referenced_table[ 'related_tables' ] as $referenced_table_related_table ) {
						$r2 = $table_to_join_related_table->getRelationship( $referenced_table_related_table );
						
						if ( $r2 ) {
							$r1 = $referenced_table[ 'table' ]->getRelationship( $referenced_table_related_table );
							$r3 = $table_to_join[ 'table' ]->getRelationship( $table_to_join_related_table );
							
							$this->createJoin( $r1 );
							$this->createJoin( $r2 );
							$this->createJoin( $r3, $this->tables_to_join[ $table_to_join_qualified_name ][ 'type' ], $this->tables_to_join[ $table_to_join_qualified_name ][ 'alias' ] );
							
							$removed = $this->tables_to_join[ $table_to_join_qualified_name ];
							$referenced_tables = array_merge( $referenced_tables, $removed );
							
							unset( $this->tables_to_join[ $table_to_join_qualified_name ] );
							reset( $this->tables_to_join );
							
							break 3;
						}
					}
				}
			}
		}
		
		if ( ! empty( $this->tables_to_join ) ) {
			throw new IllegalStateException( "Unable to join table(s) (" . implode( ' , ', array_keys( $this->tables_to_join ) ) . ")." );
		}
	}
	
	/**
	 *
	 * @param ITable $table
	 * @return boolean
	 */
	private function isReferencedTable ( ITable $table ): bool {
		return in_array( $table->getName(), $this->referenced_table_names );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IFromClause::getTableReferences()
	 */
	public function getTableReferences ( ): \ArrayObject {
		return $this->table_references;
	}
	
	/**
	 *
	 * @param ITable $table
	 */
	private function addReferencedTable ( ITable $table ) {
		$this->table_references->append( $table );
		$this->referenced_table_names[] = $table->getQualifiedName();
	}
	
	/**
	 *
	 * @return \ArrayObject
	 */
	private function getJoins ( ): \ArrayObject {
		return $this->joins;
	}
	
	private function createJoin ( ITableRelationship $relationship, $type = IFromClause::JOIN_TYPE_INNER, $alias = null): ITable {
		$join = null;
		$table = null;
		
		foreach ( $this->getTableReferences() as $table_reference ) {
			if ( $table_reference->getName() == $relationship->getTableName() ) {
				if ( $this->isReferencedTable( $relationship->getReferencedTable() ) ) {
					return $relationship->getReferencedTable();
				}
				
				$join = new Join( $relationship->getReferencedTableName(), $relationship->getReferencedColumnName(), $relationship->getTableName(), $relationship->getColumnName(), $type, $alias );
				$table = $relationship->getReferencedTable();
				
				break;
			} else if ( $table_reference->getName() == $relationship->getReferencedTableName() ) {
				if ( $this->isReferencedTable( $relationship->getTable() ) ) {
					return $relationship->getReferencedTable();
				}
				
				$join = new Join( $relationship->getTableName(), $relationship->getColumnName(), $relationship->getReferencedTableName(), $relationship->getReferencedColumnName(), $type, $alias );
				$table = $relationship->getTable();
				
				break;
			}
		}
		
		$this->joins->append( $join );
		$this->addReferencedTable( $table );
		return $table;
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString ( ) {
		$from_clause = null;
		
		try {
			$this->joinTables();
			
			$from_clause = "\nFROM `" . $this->getTableReferences()->offsetGet( 0 )->getName() . "`";
			
			foreach ( $this->getJoins() as $join ) {
				$from_clause .= $join;
			}
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}
		
		return $from_clause;
	}
}
