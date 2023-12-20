<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpressionGroup;
use Wolfgang\Application\Application;
use Wolfgang\Exceptions\MethodNotImplementedException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class ConditionalExpressionGroup extends Component implements IConditionalExpressionGroup {

	/**
	 *
	 * @var IClause
	 */
	protected $clause;

	/**
	 *
	 * @var string
	 */
	protected $logical_operator;

	/**
	 *
	 * @var string
	 */
	protected $conditional_expressions_logical_operator;

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $conditional_expressions;

	/**
	 *
	 * @param IClause $clause
	 * @param string $logical_operator
	 */
	public function __construct ( IClause $clause, $logical_operator = IWhereClause::LOGICAL_OPERATOR_AND) {
		parent::__construct();

		$this->setClause( $clause );
		$this->setLogicalOperator( $logical_operator );
	}

	protected function init ( ) {
		parent::init();

		$this->conditional_expressions = new \ArrayObject();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpressionGroup::or()
	 */
	public function or ( ) {
		$this->conditional_expressions_logical_operator = IWhereClause::LOGICAL_OPERATOR_OR;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpressionGroup::and()
	 */
	public function and ( ) {
		$this->conditional_expressions_logical_operator = IWhereClause::LOGICAL_OPERATOR_AND;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::between()
	 */
	public function between ( $expression1, $expression2, $expression3, $not = false) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->between( $expression1, $expression2, $expression3, $not );

		$this->append( $conditional_expression );
		return $this;
	}

	public function nbetween ( $expression1, $expression2, $expression3 ) {
		throw new MethodNotImplementedException();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::eq()
	 */
	public function eq ( $expression1, $expression2, $not = false) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->eq( $expression1, $expression2, $not );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::neq()
	 */
	public function neq ( $expression1, $expression ) {
		throw new MethodNotImplementedException();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::gt()
	 */
	public function gt ( $expression1, $expression2 ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->gt( $expression1, $expression2 );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::gte()
	 */
	public function gte ( $expression1, $expression2 ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->gte( $expression1, $expression2 );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::in()
	 */
	public function in ( $expression, array $values ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->in( $expression, $values );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::nin()
	 */
	public function nin ( $expression, array $values ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->nin( $expression, $values );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::is()
	 */
	public function is ( $expression, $boolean, $not = false) {
		throw new MethodNotImplementedException();
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
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->isNull( $expression );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::isNotNull()
	 */
	public function isNotNull ( $expression ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->isNotNull( $expression );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::lt()
	 */
	public function lt ( $expression1, $expression2 ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->lt( $expression1, $expression2 );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::lte()
	 */
	public function lte ( $expression1, $expresion2 ) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->lte( $expression1, $expresion2 );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Expression\IConditionalExpression::like()
	 */
	public function like ( $expression1, $expression2, $not = false) {
		$conditional_expression = new ConditionalExpression( $this->getClause(), $this->getLogicalOperator() );
		$conditional_expression->like( $expression1, $expression2, $not );

		$this->append( $conditional_expression );
		return $this;
	}

	/**
	 *
	 * @param IClause $clause
	 */
	private function setClause ( IClause $clause ) {
		$this->clause = $clause;
	}

	/**
	 *
	 * @return IClause
	 */
	public function getClause ( ): IClause {
		return $this->clause;
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
			throw new InvalidArgumentException( "Invalid value '{$logical_operator}' provided as logical operator" );
		}

		$this->logical_operator = $this->conditional_expressions_logical_operator = $logical_operator;
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
	 * @param IConditionalExpression $conditional_expression
	 */
	private function append ( IConditionalExpression $conditional_expression ) {
		$this->conditional_expressions->append( $conditional_expression );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		try {
			$expressions = [ ];

			foreach ( $this->conditional_expressions as $conditional_expression ) {
				$expressions[] = ( string ) $conditional_expression;
			}

			$expressions = ' ( ' . implode( ' ' . $this->conditional_expressions_logical_operator . ' ', $expressions ) . ' ) ';
			
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}

		return $expressions;
	}
}
