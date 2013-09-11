<?php

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
	private $_friends;
	private $_frendsOfFriends;
	private $_event;
	private $_group;
	private $_page;
	private $_user;
	private $_location;
	private $_rangeLoc;
	private $_isPhoto;
	private $_university;
	private $_sourceID;
	private $_edgeType;
	private $_resultLimit;
	private $_skip;
    private $_status; //inactive/active as we do not delete users from Neo4j
    private $_indexBy;
    
    /** @var string neo4j href */
    private $_neo4jHref;
    /** @var string neo4j port */
    private $_neo4jPort;
    /** @var class neo4j connection */
    private $_client;
    
    public function __construct()
    {
        $this->_neo4jHref = "boss.invigle.com";
        $this->_neo4jPort = "8001";
        $this->_client = new Client(new Transport($this->_neo4jHref, $this->_neo4jPort));
    }

	/**
	 * @access public
	 * @param aTermsArray
	 */
	public function graphSearch($aTermsArray) {
		// Not yet implemented
	}

	/**
     * Function to add node to Neo4j from a universal array of $params.
	 * @access public
	 * @param array
	 */
	public function addNode(array $params) {
        
        $index = new NodeIndex($this->_client, $params['indexBy']);
        
        $node = $this->_client->makeNode()->save();
        
        foreach($params as $key => $value){
            $node->setProperty($key, $value)->save();
        }
        
        $index->add($node, $params['indexBy'], $node->getProperty($params['indexBy']));
	}
    
   	/**
     * Function to edit a property in Neo4j from a universal array of $params using specified 'username'.
	 * @access public
     * @param array key/value of $params['username'] of the user to be updated.
	 * @param array of key/value pairs to update properties.
	 */
	public function editProperties(array $params) {
        $index = new NodeIndex($this->_client, $params['indexBy']);
        $node = $index->queryOne("$params[indexBy]:$params[username]");
                
        unset($params['indexBy']);
        unset($params['username']);
        
        foreach($params as $key => $value){
            $node->setProperty($key, $value)->save();
        }
        
	}

	/**
     * Function to delete node from Neo4j using an array containing 'indexBy' and 'indexValue'
	 * @access public
	 * @param array
	 */
	public function deleteNode(array $params) {
        $index = new NodeIndex($this->_client, $params['indexBy']);
        $node = $index->queryOne("$params[indexBy]:$params[indexValue]");
        
        $node->delete();
	}
    
    /**
     * Function to delete node from Neo4j using the nodeID.
	 * @access public
	 * @param $aID
	 */
	public function deleteNodeByID($aID) {
        $node->$this->_client->getNode($aID);        
        $node->delete();
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function addConnection($aID1, $aID2, $aType) {
        $node1->$this->_client->getNode(int($aID1));
        //$node2->$this->_client->getNode($aID2);
        //$node1->relateTo($node2, "$aType")->save();
        
        print '<pre>';
        print_r($node1);
        print '</pre>';
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function deleteConnection($aID1, $aID2, $aType) {
		
	}

	/**
	 * @access public
	 * @param aID
	 * @param aType
	 * @param aSkip
	 * @param aLimit
	 */
	public function listNodes($aID, $aType, $aSkip, $aLimit) {
		// Not yet implemented
	}
}
?>