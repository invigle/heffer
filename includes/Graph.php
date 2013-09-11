<?php
require_once(realpath(dirname(__FILE__)) . '/Search.php');
require_once(realpath(dirname(__FILE__)) . '/neo4jphp.phar');

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

/**
 * @access public
 * @author Grant
 */
class Graph extends Search {
	public $_friends;
	public $_frendsOfFriends;
	public $_event;
	public $_group;
	public $_page;
	public $_user;
	public $_location;
	public $_rangeLoc;
	public $_isPhoto;
	public $_university;
	public $_sourceID;
	public $_edgeType;
	public $_resultLimit;
	public $_skip;
    
    /** @var string neo4j href */
    private $_neo4jHref;
    /** @var string neo4j port */
    private $_neo4jPort;
    
    public function __construct()
    {
        $this->_neo4jHref = "boss.invigle.com";
        $this->_neo4jPort = "8001";
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
        
        $client = new Client(new Transport($this->_neo4jHref, $this->_neo4jPort));
        $node = new Node($client);
        
        foreach($params as $key => $value){
            $node->setProperty($key, $value)->save();
        }
        
        return $node;
	}

	/**
	 * @access public
	 * @param aID
	 */
	public function deleteNode($aID) {
		// Manos Panaousis
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function addConnection($aID1, $aID2, $aType) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function deleteConnection($aID1, $aID2, $aType) {
		// Not yet implemented
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

	/**
	 * @access public
	 * @param aID
	 */
	public function getIndex($aID) {
		// Not yet implemented
	}
}
?>