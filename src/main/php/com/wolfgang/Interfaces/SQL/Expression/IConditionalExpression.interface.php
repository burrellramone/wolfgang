<?php

namespace Wolfgang\Interfaces\SQL\Expression;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IConditionalExpression {
	const QUALIFIER_NOT = 1;
	const BOOLEAN_TRUE = 1;
	const BOOLEAN_FALSE = 2;
	const BOOLEAN_UNKNOWN = 3;
	// Operators
	const LOGICAL_OPERATOR_AND = 'AND';
	const LOGICAL_OPERATOR_OR = 'OR';
	const OPERATOR_EQUAL = '=';
	const OPERATOR_NOT_EQUAL = '<>';
	const OPERATOR_GREATER_THAN = '>';
	const OPERATOR_GREATER_THAN_OR_EQUAL_TO = '>=';
	const OPERATOR_LESS_THAN = '<';
	const OPERATOR_LESS_THAN_OR_EQUAL_TO = '<=';
	const OPERATOR_IN = 'IN';
	const OPERATOR_NOT_IN = 'NOT IN';
	const OPERATOR_BETWEEN = 'BETWEEN';
	const OPERATOR_NOT_BETWEEN = 'NOT BETWEEN';
	const OPERATOR_IS = 'IS';
	const OPERATOR_IS_NOT = 'IS NOT';
	const OPERATOR_LIKE = 'LIKE';
	const OPERATOR_NOT_LIKE = 'NOT LIKE';
	const OPERATOR_IS_NULL = 'IS NULL';
	const OPERATOR_IS_NOT_NULL = 'IS NOT NULL';
	
	public function between ( $expression1, $expression2, $expression3 );
	
	public function nbetween ( $expression1, $expression2, $expression3 );
	
	public function eq ( $expression1, $expression );
	
	public function neq ( $expression1, $expression );
	
	public function gt ( $expression1, $expresion2 );
	
	public function gte ( $expression1, $expresion2 );
	
	public function in ( $expression, array $values );
	
	public function nin ( $expression, array $values );
	
	public function is ( $expression, $boolean );
	
	public function nis ( $expression, $boolean );
	
	public function isNull ( $expression );
	
	public function isNotNull ( $expression );
	
	public function lt ( $expression1, $expresion2 );
	
	public function lte ( $expression1, $expresion2 );
	
	public function like ( $expression1, $expresion2, $not = false);
}