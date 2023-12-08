<?php

namespace Wolfgang\Database\MySQL;

use Wolfgang\Interfaces\Database\MySQL\IMySQLResultSet;
use Wolfgang\Database\ResultSet as DatabaseResultSet;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\IllegalStateException;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */

final class ResultSet extends DatabaseResultSet implements IMySQLResultSet {

	/**
	 *
	 * @param \mysqli_result $result
	 */
	public function __construct ( \mysqli_result $result ) {
		parent::__construct( $result );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Database\IResultSet::get()
	 */
	public function get ( $index ): array {
		if ( ! $this->count() ) {
			throw new IllegalStateException( "There are no records within this result set" );
		} else if ( $index > ($this->count() - 1) ) {
			throw new IllegalArgumentException( "Illegal offset provided" );
		} else if ( $this->position != $index ) {
			$this->position = $index;
			$this->result->data_seek( $index );
		}

		return $this->result->fetch_assoc();
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Database\IResultSet::fetchAll()
	 */
	public function fetchAll ( ): array {
		return $this->result->fetch_all( MYSQLI_ASSOC );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Database\IResultSet::getColumns()
	 */
	public function getColumns ( ): array {
		$columns = $this->result->fetch_fields();

		foreach ( $columns as $key => $column ) {
			$columns[ $key ] = ( array ) $column;
		}

		return $columns;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Countable::count()
	 */
	public function count ( ):int {
		return $this->result->num_rows;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::current()
	 */
	public function current ( ):mixed {
		return $this->get( $this->position );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::key()
	 */
	public function key ( ):mixed {
		return $this->position;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::next()
	 */
	public function next ( ):void {
		$this->position ++;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::rewind()
	 */
	public function rewind ( ):void {
		$this->position = 0;
		$this->result->data_seek( $this->position );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::valid()
	 */
	public function valid ( ):bool {
		return ($this->position < $this->count());
	}
}
