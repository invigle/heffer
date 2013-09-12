<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Graph {
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
    }
    
    /**
     * Function to interface with the Neo4J RESTfull API.
     * @access public
     * Usage:
     * $path = Path within RESTful API, i.e. /nodes/26
     * $type = GET, POST, JSONPOST
     * $postfields = Required for POST or JSONPOST, NULL for GET.
     */
    public function neo4japi($path, $type='GET', $postfields=array())
    {
    	$url = "http://$this->_neo4jHref:$this->_neo4jPort/db/data/$path";
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        if($type === "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);  
        }elseif($type === "JSONPOST"){
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($postfields));
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }elseif($type === "PUT"){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($postfields));
        }
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$data = curl_exec($ch);
    	curl_close($ch);
    
        $json = json_decode($data, true);
        return $json;
    }
    
   	/**
     * Function to edit a property in Neo4j from a universal array of $params using indexBy and indexValue to identify the nodes ID#.
	 * @access public
	 * @param array of key/value pairs to update properties.
	 */
	public function editProperties(array $params)
    {   
       $path = "cypher";
       $postfields['query'] = "START n=node:$params[indexBy]($params[indexBy] = '$params[indexValue]') RETURN n;";
       $api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
       $node = explode("/", $api['data']['0']['0']['self']);
       $nodeId = end($node);
       
       //Unset params that we do not want to be saved in the Properties of the node.
       unset($params['indexBy'], $params['indexValue']);
       
       //Copy all existing params into a new array checking recursively for updates in the params argument.
       //$newParams = $params;
       foreach($api['data'][0][0]['data'] as $key => $value){
            $newParams[$key] = $value;
       }
 
       //Update the nodes Properties
       //$nodePath = "node/$nodeId/properties";
       //$setApi = $this->neo4japi($nodePath, 'PUT', $params);
       
       print "NODE ID: $nodeId<hr>";
       print '<pre>';
       print_r($api);
       print_r($newParams);
       print '</pre>';
	}

	/**
     * Function to delete node from Neo4j using an array containing 'indexBy' and 'indexValue'
	 * @access public
	 * @param array
	 */
	public function deleteNode(array $params) {

	}
    
    /**
     * Function to delete node from Neo4j using the nodeID.
	 * @access public
	 * @param $aID
	 */
	public function deleteNodeByID($aID) {

	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function addConnection($aID1, $aID2, $aType) {

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