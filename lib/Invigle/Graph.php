<?php

namespace Invigle;

/**
 * @access public
 * @author Gavin Hanson
 */
class Graph
{
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

	/**
	 *  *  *  * @var string neo4j href */
	private $_neo4jHref;
	/**
	 *  *  *  * @var string neo4j port */
	private $_neo4jPort;
    private $_neo4jurlprefix;
	/**
	 *  *  *  * @var class neo4j connection */
	private $_client;

	public function __construct()
	{
	    $this->_neo4jurlprefix = "https";
		$this->_neo4jHref = "127.0.0.1";
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
	public function neo4japi($path, $type = 'GET', $postfields = array())
	{
		$url = "$this->_neo4jurlprefix://$this->_neo4jHref:$this->_neo4jPort/db/data/$path";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        if ($type === "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		
        } elseif ($type === "JSONPOST") {
			curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		
        } elseif ($type === "PUT") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		
        } elseif ($type === "DELETE") {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		
        }
		
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
        
		$json = json_decode($data, true);
		return $json;
	}

	/**
	 * Find a node using cypher.
     * 
     * Required variables:
     * nodeType         The type of node for example User, Event, Group, Page, Status
     * indexBy          The field to index by, for example username, email or sessionid
     * indexValue       The actual search term i.e. john@smith.com.
     * 
     * These values must be passed as an array to the getNodeId Function.
	 */
	public function getNodeId(array $params)
	{
		$postfields['query'] = "MATCH n:$params[nodeType] WHERE $params[indexBy]='$params[indexValue]' RETURN n;";
        //$postfields['query'] = "START n=node:$params[indexBy]($params[indexBy] = '$params[indexValue]') RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$node = explode("/", $api['data']['0']['0']['self']);
			return end($node);
		}
	}

	/**
	 * Find an edge using cypher indexBy and indexValue
	 */
	public function getConnectionId(array $params)
	{
		{
			$path = "cypher";
			$postfields['query'] = "START n=edge:$params[indexBy]($params[indexBy] = '$params[indexValue]') RETURN n;";
			$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
			if (isset($api['data'][0]))
			{
				$connection = explode("/", $api['data']['0']['0']['self']);
				return end($connection);
			}
		}
	}


	/**
	 * Function to edit a property in Neo4j from a universal array of $params.
     * 
     * Required variables:
     * nodeType         The type of node for example User, Event, Group, Page, Status
     * indexBy          The field to index by, for example username, email or sessionid
     * indexValue       The actual search term i.e. john@smith.com.
     * 
	 * @access public
	 * @param array of key/value pairs to update properties.
	 */
	public function editNodeProperties(array $params)
	{
		//Get the Node ID#
		$nodeId = $this->getNodeId($params);

		//Unset params that we do not want to be saved in the Properties of the node.
		unset($params['indexBy'], $params['indexValue'], $params['nodeType']);

		//Copy all existing params into a new array checking recursively for updates in the params argument.
		//$newParams = $params;
		foreach ($api['data'][0][0]['data'] as $key => $value)
		{
			if (isset($params[$key]))
			{
				$newParams[$key] = $params[$key];
			} else
			{
				$newParams[$key] = $value;
			}
		}

		//Check for new Params
		foreach ($params as $key => $value)
		{
			if (!isset($params[$key]))
			{
				$newParams[$key] = $value;
			}
		}

		//Update the nodes Properties
		$nodePath = "node/$nodeId/properties";
		$setApi = $this->neo4japi($nodePath, 'PUT', $newParams);
	}

	// An edge has two properties: type and uID
	/**
	 * Function to edit a property of an edge in Neo4j.
	 * @access public
	 * @param array of key/value pairs to update properties.
	 */
	public function editConnectionProperties(array $params)
	{
		{
		  //params[0]: edge type
			//Get the Edge ID#
			$connectionId = $this->getConnectionId($params);

			//Unset params that we do not want to be saved in the Properties of the edge.
			unset($params['indexBy'], $params['indexValue']);

			//Copy all existing params into a new array checking recursively for updates in the params argument.
			//$newParams = $params;
			foreach ($api['data'][0][0]['data'] as $key => $value)
			{
				if (isset($params[$key]))
				{
					$newParams[$key] = $params[$key];
				} else
				{
					$newParams[$key] = $value;
				}
			}

			//Check for new Params
			foreach ($params as $key => $value)
			{
				if (!isset($params[$key]))
				{
					$newParams[$key] = $value;
				}
			}

			//Update the edges Properties
			$connectionPath = "node/$connectionId/properties";
			$setApi = $this->neo4japi($connectionPath, 'PUT', $newParams);
		}
	}


	/**
	 * Function to delete node from Neo4j using an array containing 'indexBy' and 'indexValue'
     * 
     * Required variables:
     * nodeType         The type of node for example User, Event, Group, Page, Status
     * indexBy          The field to index by, for example username, email or sessionid
     * indexValue       The actual search term i.e. john@smith.com.
     * 
	 * @access public
	 * @param array
	 */
	public function deleteNode(array $params)
	{
		//Get the Node ID#
		$nodeId = $this->getNodeId($params);

		//Delete the Node
		$path = "node/$nodeId";
		$api = $this->neo4japi($path, 'DELETE');
	}

	/**
	 * Function to delete node from Neo4j using the nodeID.
	 * @access public
	 * @param $aID
	 */
	public function deleteNodeByID($aID)
	{
		//Delete the Node
		$path = "node/$aID";
		$api = $this->neo4japi($path, 'DELETE');
	}

	/**
	 * Add a Relationship between two nodes (aID1 and aID2) of Type = $aType.
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function addConnection($aID1, $aID2, $aType)
	{
		$arr['query'] = "START n1=node($aID1), n2=node($aID2) CREATE n1-[fr:$aType]->n2 RETURN fr;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $arr);
	}

	/**
	 * Remove a Relationship between two nodes (aID1 and aID2) of Type = $aType.
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function deleteConnection($aID1, $aID2, $aType)
	{
		$del['query'] = "START n=node($aID1), n2=node($aID2) MATCH n-[r:$aType]-n2 DELETE r;";
		$delApi = $this->neo4japi('cypher', 'JSONPOST', $del);
	}

	/**
	 * @access public
	 * @param aID (starting point).
	 * @param aType
	 * @param aSkip
	 * @param aLimit
	 */
	public function listNodes($aID, $aType, $aSkip, $aLimit)
	{
		$list['query'] = "START n=node($aID) MATCH n-[:$aType]-rtn RETURN rtn SKIP $aSkip LIMIT $aLimit;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $list);

		return $api['data'];
	}
    
    /**
     * Count Nodes
     * 
     * @params $nodeType, $key, $value
     * @return boolean
     */
    public function countNodes($nodeType, $key = "none", $value = "none")
    {
        if($key === "none"){
            $a['query'] = "MATCH n:$nodeType RETURN count(*);";
        }else{
            $a['query'] = "MATCH n:$nodeType WHERE n.$key = \"$value\" RETURN count(*);";    
        }
        
        $api = $this->neo4japi('cypher', 'JSONPOST', $a);
        
    return $api['data'][0][0];
    }
    
    /**
     * Create a Node
     * 
     * @params $nodeType, $properties
     * @return nodeId
     */
    public function createNode($nodeType, $properties = array())
    {
        $queryString = "";
		foreach ($properties as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$user['query'] = "CREATE (n:$nodeType {" . $queryString . "}) RETURN n;";

		$apiCall = $this->neo4japi('cypher', 'JSONPOST', $user);
        
        $bit = explode("/", $apiCall['data'][0][0]['self']);
		$nodeId = end($bit);

    return $nodeId;
    }
    
    /**
     * Match Nodes
     * 
     * @params $nodeType, $key, $value
     * @return array
     */
    public function matchNode($nodeType, $key, $value)
    {
        $run['query'] = "MATCH n:$nodeType WHERE n.$key = '$value' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $run);
  
    return $api;
    }
    
    /**
     * Update Node
     * 
     * @params $nodeType, $key, $value, $properties
     * @return array
     */
    public function updateNode($nodeType, $key, $value, $properties = array())
    {
        $queryString = "";
        foreach($properties as $a => $i){
            $queryString.= "SET n.$a='$i' ";
        }
        $q['query'] = "MATCH n:$nodeType WHERE n.$key = '".$value."' ".$queryString."RETURN n;";
        $update = $this->neo4japi('cypher', 'JSONPOST', $q);
        
    return $update;
    }
    
    /**
     * Update Node With NodeID
     * 
     * @params $nodeId, $properties
     * @return array
     */
    public function updateNodeById($nodeId, $properties = array())
    {
        $queryString = "";
        foreach($properties as $a => $i){
            $queryString.= "SET n.$a='$i' ";
        }
        $q['query'] = "START n=node($nodeId) ".$queryString."RETURN n;";
        $update = $this->neo4japi('cypher', 'JSONPOST', $q);
        
    return $update;
    }
    
    /**
     * Select Node by ID
     * 
     * @param $id
     * @return array()
     */
    public function selectNodeById($id)
    {
        $a['query'] = "START n=node($id) RETURN n;";
        $rtn = $this->neo4japi('cypher', 'JSONPOST', $a);
        
    return $rtn;
    }
    
    /**
     * Transverse Nodes
     * 
     * @param $startNode [ID of Node to start with i.e. the UserID]
     * @param $edgeType [Type of Edge to Transverse i.e. timeline]
     * @param $startRow [First row to return].
     * @param $endRow [Last row to return].
     * @return array()
     */
    public function transverseNodes($startNode, $edgeType, $startRow='1', $endRow='10')
    {
        $a['query'] = "START n=node($startNode) MATCH n-[:$edgeType*$startRow..$endRow]-o RETURN o;";
        $api = $this->neo4japi('cypher', 'JSONPOST', $a);
    
    return $api['data'];
    }
    
}

?>