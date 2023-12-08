<?php

namespace Wolfgang\SQL;

use Wolfgang\Interfaces\SQL\Clause\IFromClause;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Join extends Component {

	/**
	 *
	 * @var string
	 */
	protected $type;

	/**
	 *
	 * @var string
	 */
	protected $table_name_1;

	/**
	 *
	 * @var string
	 */
	protected $column_name_1;

	/**
	 *
	 * @var string
	 */
	protected $table_name_2;

	/**
	 *
	 * @var string
	 */
	protected $column_name_2;

	/**
	 *
	 * @var string
	 */
	protected $alias;

	/**
	 *
	 * @param string $table_name_1
	 * @param string $column_name_1
	 * @param string $table_name_2
	 * @param string $column_name_2
	 * @param string $type
	 * @param string $alias
	 */
	public function __construct ( $table_name_1, $column_name_1, $table_name_2, $column_name_2, $type = IFromClause::JOIN_TYPE_INNER, $alias = '' ) {
		$this->table_name_1 = $table_name_1;
		$this->column_name_1 = $column_name_1;
		$this->table_name_2 = $table_name_2;
		$this->column_name_2 = $column_name_2;
		$this->type = $type;
		$this->alias = $alias;

		parent::__construct();
	}

	protected function init ( ) {
		parent::init();
	}

	public function getType ( ) {
		return $this->type;
	}

	public function getTableName1 ( ) {
		return $this->table_name_1;
	}

	public function getColumnName1 ( ) {
		return $this->column_name_1;
	}

	public function getTableName2 ( ) {
		return $this->table_name_2;
	}

	public function getColumnName2 ( ) {
		return $this->column_name_2;
	}

	/**
	 *
	 * @return string|null
	 */
	public function getAlias ( ) {
		return $this->alias;
	}

	private function getString ( ) {
		return "\n {$this->getType()} `{$this->getTableName1()}` {$this->getAlias()} ON `{$this->getTableName1()}`.`{$this->getColumnName1()}` = `{$this->getTableName2()}`.`{$this->getColumnName2()}`";
	}

	public function __toString ( ) {
		return $this->getString();
	}
}
