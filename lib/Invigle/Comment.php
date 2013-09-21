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

	// The ID of the latest comment added to the post/photo/status
	public $_latestCommID;

	/* The Class Constructor*/
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
		$succ = $graph->addConnection($commentID, $statusID, $connectionType);
		return $succ;
	}

	public function deleteCommentStatus($commentID, $statusID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $statusID, $connectionType);
		return $succ;
	}

	public function addCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->addConnection($commentID, $postID, $connectionType);
		return $succ;
	}

	public function deleteCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $postID, $connectionType);
		return $succ;
	}

	public function addPhotoComment($commentID, $photoID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->addConnection($commentID, $photoID, $connectionType);
		return $succ;
	}

	public function deletePhotoComment($commentID, $photoID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $photoID, $connectionType);
		return $succ;
	}

	public function connectComments($commentID, $commentID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
		$succ = $graph->addConnection($commentID, $commentID2, $connectionType);
		return $succ;
	}

	public function disconnectComments($commentID, $commentID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
		$succ = $graph->deleteConnection($commentID, $commentID2, $connectionType);
		return $succ;
	}

	public function getLatestComment()
	{
		return $this->_latestCommID;
	}

	public function updateLatestComment($cArray, $commentID, $nodeID, $nodeType)
	{
		$newCommId = $this->createComment($cArray);
		$currLatestId = $this->getLatestComment();
		if ($nodeType == 'photo')
		{
			$photoID = $nodeID;
			$succ = $this->deletePhotoComment($currLatestId, $photoID);
			if (!$succ)
			{
				throw new Exception("Latest comment on photo $photoID could not be deleted.");
			}
			$succ = $this->addPhotoComment($newCommId, $photoID);
			if (!$succ)
			{
				throw new Exception("New comment on photo $photoID could not be added.");
			}
		}
		if ($nodeType == 'status')
		{
			$statusID = $nodeID;
			$succ = $this->deleteCommentStatus($currLatestId, $statusID);
			if (!$succ)
			{
				throw new Exception("Latest comment on status $statusID could not be deleted.");
			}
			$succ = $this->addStatusComment($newCommId, $statusID);
			if (!$succ)
			{
				throw new Exception("New comment on status $statusID could not be added.");
			}
		}
		if ($nodeType == 'post')
		{
			$postID = $nodeID;
			$succ = $this->deleteCommentPost($currLatestId, $postID);
			if (!$succ)
			{
				throw new Exception("Latest comment on post $postID could not be deleted.");
			}
			$succ = $this->addPostComment($newCommId, $postID);
			if (!$succ)
			{
				throw new Exception("New comment on post $postID could not be added.");
			}
		}
		$succ = $this->connectComments($newCommId, $currLatestId);
		if (!$succ)
		{
			throw new Exception("New comment $newCommId could not be connected to the previous latest message $currentId.");
		}
		$this->_latestCommID = $newCommId;
	}
}

?>