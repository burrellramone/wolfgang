<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Exceptions\SQL\Exception as SQLException;
use Wolfgang\Application\Application;
use Wolfgang\Exceptions\MethodNotImplementedException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\SQL\Expression
 * @since Version 1.0.0
 */
final class ConditionalExpression extends Expression implements IConditionalExpression {

	/**
	 *
	 * @var string
	 */
	protected $logical_operator;

	/**
	 *
	 * @var string
	 */
	protected $comparison_operator;

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $expressions;

	/**
	 *
	 * @param IClause $clause
	 * @param string $logical_operator
	 */
	public function __construct ( IClause $clause, $logical_operator = IWhereClause::LOGICAL_OPERATOR_AND) {
		$this->setLogicalOperator( $logical_operator );

		parent::__construct( $clause );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\Expression::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->expressions = new \ArrayObject( [ ] );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::between()
	 */
	public function between ( $expression1, $expression2, $expression3 ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_BETWEEN;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression3 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::nbetween()
	 */
	public function nbetween ( $expression1, $expression2, $expression3 ) {
		throw new MethodNotImplementedException( "" );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::eq()
	 */
	public function eq ( $expression1, $expression2 ) {
		if ( $expression2 === null ) {
			return $this->isNull( $expression1 );
		}

		$this->comparison_operator = IConditionalExpression::OPERATOR_EQUAL;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::neq()
	 */
	public function neq ( $expression1, $expression ) {
		throw new MethodNotImplementedException( "" );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::gt()
	 */
	public function gt ( $expression1, $expression2 ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_GREATER_THAN;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::gte()
	 */
	public function gte ( $expression1, $expression2 ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_GREATER_THAN_OR_EQUAL_TO;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::in()
	 */
	public function in ( $expression, array $values ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_IN;

		$this->expressions->append( Expression::create( $this->clause, $expression ) );
		$this->expressions->append( Expression::create( $this->clause, $values ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::nin()
	 */
	public function nin ( $expression, array $values ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_NOT_IN;

		$this->expressions->append( Expression::create( $this->clause, $expression ) );
		$this->expressions->append( Expression::create( $this->clause, $values ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::is()
	 */
	public function is ( $expression, $boolean ) {
		throw new MethodNotImplementedException( "" );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::nis()
	 */
	public function nis ( $expression, $boolean ) {
		throw new MethodNotImplementedException();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::isNull()
	 */
	public function isNull ( $expression ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_IS_NULL;
		$this->expressions->append( Expression::create( $this->clause, $expression ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::isNotNull()
	 */
	public function isNotNull ( $expression ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_IS_NOT_NULL;
		$this->expressions->append( Expression::create( $this->clause, $expression ) );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::lt()
	 */
	public function lt ( $expression1, $expression2 ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_LESS_THAN;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::lte()
	 */
	public function lte ( $expression1, $expression2 ) {
		$this->comparison_operator = IConditionalExpression::OPERATOR_LESS_THAN_OR_EQUAL_TO;

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::like()
	 */
	public function like ( $expression1, $expression2, $not = false) {
		if ( $not ) {
			$this->comparison_operator = IConditionalExpression::OPERATOR_NOT_LIKE;
		} else {
			$this->comparison_operator = IConditionalExpression::OPERATOR_LIKE;
		}

		$this->expressions->append( Expression::create( $this->clause, $expression1 ) );
		$this->expressions->append( Expression::create( $this->clause, $expression2 ) );

		return $this;
	}

	/**
	 *
	 * @param string $logical_operator
	 * @return null
	 */
	private function setLogicalOperator ( $logical_operator ) {
		if ( empty( $logical_operator ) ) {
			throw new IllegalArgumentException( "Logical operator not provided" );
		} else if ( ($logical_operator != IWhereClause::LOGICAL_OPERATOR_AND) && ($logical_operator != IWhereClause::LOGICAL_OPERATOR_OR) ) {
			throw new InvalidArgumentException( "Invalid value '{$logical_operator}' provided provided for logical operator" );
		}

		$this->logical_operator = $logical_operator;
	}

	/**
	 *
	 * @return string
	 */
	public function getLogicalOperator ( ) {
		return $this->logical_operator;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		$conditional_expression = '';

		try {

			switch ( $this->comparison_operator ) {
				case IConditionalExpression::OPERATOR_EQUAL :
				case IConditionalExpression::OPERATOR_NOT_EQUAL :
				case IConditionalExpression::OPERATOR_GREATER_THAN :
				case IConditionalExpression::OPERATOR_GREATER_THAN_OR_EQUAL_TO :
				case IConditionalExpression::OPERATOR_LESS_THAN :
				case IConditionalExpression::OPERATOR_LESS_THAN_OR_EQUAL_TO :
				case IConditionalExpression::OPERATOR_IN :
				case IConditionalExpression::OPERATOR_NOT_IN :

					$conditional_expression .= " ( " . $this->expressions->offsetGet( 0 );

					$conditional_expression .= " {$this->comparison_operator} ";

					$conditional_expression .= $this->expressions->offsetGet( 1 ) . " ) ";

					break;
				case IConditionalExpression::OPERATOR_BETWEEN :

					$conditional_expression .= " ( " . $this->expressions->offsetGet( 0 ) . " ";

					$conditional_expression .= " {$this->comparison_operator} ";

					$conditional_expression .= " " . $this->expressions->offsetGet( 1 ) . " ";

					$conditional_expression .= " AND ";

					$conditional_expression .= " " . $this->expressions->offsetGet( 2 ) . " ) ";

					break;

				case IConditionalExpression::OPERATOR_IS_NULL :
				case IConditionalExpression::OPERATOR_IS_NOT_NULL :

					$conditional_expression .= " ( " . $this->expressions->offsetGet( 0 ) . " ";

					$conditional_expression .= " {$this->comparison_operator} ) ";

					break;

				case IConditionalExpression::OPERATOR_LIKE :
				case IConditionalExpression::OPERATOR_NOT_LIKE :

					$conditional_expression .= " ( " . $this->expressions->offsetGet( 0 ) . " ";

					$conditional_expression .= " {$this->comparison_operator} ";

					$conditional_expression .= " " . $this->expressions->offsetGet( 1 ) . " ) ";

					break;

				default :
					throw new SQLException( "Invalid operator '{$this->comparison_operator}' provided for predicate condition" );
					break;
			}
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}

		return $conditional_expression;
	}
}
