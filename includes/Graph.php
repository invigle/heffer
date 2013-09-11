<?php
require_once(realpath(dirname(__FILE__)) . '/Search.php');
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
        $rtn = $index->queryOne("$params[indexBy]:$params[username]");
        
        echo $rtn["id:protected"];
        
        print '<br />RETURN:<hr><pre>';
        print_r($rtn);
        print '</pre>';
	}
    
   	/**
     * Function to edit a property in Neo4j from a universal array of $params using specified 'username'.
	 * @access public
     * @param array key/value of $params['username'] of the user to be updated.
	 * @param array of key/value pairs to update properties.
	 */
	public function editProperties(array $params) {
        
        $client = new Client(new Transport($this->_neo4jHref, $this->_neo4jPort));
        
        $index = new NodeIndex($this->_client, $params['indexBy']);
        $arr = $index->queryOne("$params[indexBy]:$params[username]")->getProperty('id');
        
        print "Node ID: $arr";
                
        unset($params['indexBy']);
        unset($params['username']);
        
        foreach($params as $key => $value){
            $node->setProperty($key, $value)->save();
        }
	}

	/**
     * Function to delete node from Neo4j from a node ID
	 * @access public
	 * @param aID
	 */
	public function deleteNode($aID) {
 
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