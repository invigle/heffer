<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access public
 * @author Manos
 */
class Comment
{
	private $_comment;
	private $_date;
	private $_cID;
	private $_sID;
	private $_pHID;
	private $_eID;
	private $_gID;
	private $_uID;
	private $_pID;
	private $_nodeType;

	public function __construct()
	{
		$this->_nodeType = 'Comment';
	}
 
	/**
	 * This method takes as input an array with all the information of a comment and 
	 * adds this comment to the GD as a 'comment node'.
	 * @access public
	 * @param cArray
	 * @return integer
	 */
	public function createComment($cArray)
	{
		//Create the new comment in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($cArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$comment['query'] = "CREATE (n:Comment {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $comment);

		//return the new comment ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$commentId = end($bit);
		return $commentId;
	}

	/** Function to delete a comment node given an ID.
	 * @access private
	 * @param cID
	 * @return boolean
	 */
	public function deleteComment($cID)
	{
		$graph = new Graph();
		$succDelete = $graph->deleteNodeByID($cID);
		return $succDelete;
	}

	/**
	 * This method edits some of the properties of a comment in the GD by updating the current node in 
	 * the GD with information provided by the cArray which is the input to the editComment method
	 * @access public
	 * @param cArray
	 * @return boolean
	 */
	public function editComment($cArray)
	{
		$graph = new Graph();
		$succEdit = $graph->editNodeProperties($cArray);
		return $succEdit;
	}
	
    public function addCommentStatus($commentID, $statusID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        //$statusID = getStatusId($statusParams);
		$succ = $graph->addConnection($commentID, $statusID, $connectionType);
		return $succ;
	}
   
    public function deleteCommentStatus($commentID, $statusID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        //$statusID = getStatusId($statusParams);
		$succ = $graph->deleteConnection($commentID, $statusID, $connectionType);
		return $succ;
	}
	
    public function connectComments($commentID, $commentID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
        //$commentID2 = getCommentId($commentParams);
		$succ = $graph->addConnection($commentID, $commentID2, $connectionType);
		return $succ;
	}

	public function disconnectComments($commentID, $commentID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
        //$commentID2 = getCommentId($commentParams);
		$succ = $graph->deleteConnection($commentID, $commentID2, $connectionType);
		return $succ;
	}
     
    public function addCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        //$postID = getPostId($postParams);
		$succ = $graph->addConnection($commentID, $postID, $connectionType);
		return $succ;
	}
    
    public function deleteCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        //$postID = getPostId($postParams);
		$succ = $graph->deleteConnection($commentID, $postID, $connectionType);
		return $succ;
	}
    
    public function addPhotoComment($commentID, $photoID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        //$photoID = getPhotoId($postParams);
		$succ = $graph->addConnection($commentID, $photoID, $connectionType);
		return $succ;
	}

    public function deletePhotoComment($commentID, $postParams)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
        $photoID = getPhotoId($postParams);
		$succ = $graph->deleteConnection($commentID, $photoID, $connectionType);
		return $succ;
	}
    
    /*--public function getStatusId(array $params)
	{
		$path = "cypher";
		$postfields['query'] = "MATCH n:Status WHERE n.$params[indexBy]='$params[indexValue]' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$statusID = explode("/", $api['data']['0']['0']['self']);
			return end($statusID);
		}
	}*/
    
	/*public function getPostId(array $params)
	{
		$path = "cypher";
		$postfields['query'] = "MATCH n:Post WHERE n.$params[indexBy]='$params[indexValue]' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$postID = explode("/", $api['data']['0']['0']['self']);
			return end($postID);
		}
	}*/
    
    /*public function getPhotoId(array $params)
	{
		$path = "cypher";
		$postfields['query'] = "MATCH n:Photo WHERE n.$params[indexBy]='$params[indexValue]' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$photoID = explode("/", $api['data']['0']['0']['self']);
			return end($photoID);
		}
	}*/
    
    /*public function getCommentId(array $params)
	{
		$path = "cypher";
		$postfields['query'] = "MATCH n:Comment WHERE n.$params[indexBy]='$params[indexValue]' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$commentID = explode("/", $api['data']['0']['0']['self']);
			return end($commentID);
		}
	}*/
}

?>