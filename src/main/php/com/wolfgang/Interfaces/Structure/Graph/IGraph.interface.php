<?php

namespace Wolfgang\Interfaces\Structure\Graph;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IGraph {

	public function save ( );

	/**
	 * Adds a node to this tree
	 *
	 * @param IGraphNode $node
	 */
	public function add ( IGraphNode $node );

	/**
	 *
	 * @param string $node_name
	 * @return IGraphNode|null An instance of IGraphNode if the node was found in the graph, null
	 *         otherwise
	 */
	public function find ( string $node_name );
}
