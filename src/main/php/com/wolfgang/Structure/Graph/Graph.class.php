<?php

namespace Wolfgang\Structure\Graph;

//PHP
use \Exception;

//Wolfgang
use Wolfgang\Interfaces\Structure\Graph\IGraph;
use Wolfgang\Interfaces\Structure\Graph\IGraphNode;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Graph extends Component implements IGraph , \Countable {

	/**
	 * A map of the nodes within this graph for index access of them
	 *
	 * @var array
	 */
	private $map = [ ];
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraph::save()
	 */
	abstract public function save ( );

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraph::add()
	 */
	public function add ( IGraphNode $node ) {
		$node_name = $node->getName();

		if(isset($this->map[$node_name])) {
			throw new Exception("Cannot re-add existing node '{$node_name}' to graph.");
		}

		$this->map[ $node_name ] = $node;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraph::find()
	 */
	public function &find ( string $node_name ) {
		$node = null;
		if ( ! empty( $this->map[ $node_name ] ) ) {
			$node = $this->map[ $node_name ];
		}

		return $node;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Countable::count()
	 */
	public function count ( ): int {
		return count( $this->map );
	}

	/**
	 *
	 * @return array
	 */
	public function getMap ( ): array {
		return $this->map;
	}
}
