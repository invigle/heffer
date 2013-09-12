<?php

/**
 * @author Gavin Hanson
 * @copyright 2013
 */

namespace Invigle;

use Invigle\Search;

require_once(realpath(dirname(__FILE__)) . '/neo4jphp.phar');

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index\NodeIndex,
    Everyman\Neo4j\Index\RelationshipIndex,
    Everyman\Neo4j\Index\NodeFulltextIndex,
    Everyman\Neo4j\Batch;

/**
 * @access public
 * @author Grant
 */
class Graph extends Search {
    
    	/**
	 * @access public
	 * @param aTermsArray
	 */
	public function graphSearch($aTermsArray) {
		// Not yet implemented
	}
}
?>