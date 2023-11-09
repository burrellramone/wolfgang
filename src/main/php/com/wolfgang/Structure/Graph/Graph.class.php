<?php

namespace Wolfgang\Structure\Graph;

use Wolfgang\Interfaces\Structure\Graph\IGraph;
use Wolfgang\Interfaces\Structure\Graph\IGraphNode;

/**
 *
 * @package Wolfgang\Structures
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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
		$this->map[ $node->getName() ] = $node;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraph::find()
	 */
	public function &find ( $node_name ) {
		$null = null;
		if ( ! empty( $this->map[ $node_name ] ) ) {
			$node = $this->map[ $node_name ];
			return $node;
		}
		return $null;
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
