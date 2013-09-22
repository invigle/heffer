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
	private $_nodeType;
	private $_cID;
	private $_sID;
	private $_pHID;
	private $_eID;
	private $_gID;
	private $_uID;
	private $_pID;

	// The ID of the latest comment added to the post/photo/status
	public $_latestCommID;

	/* The Class Constructor*/
	public function __construct()
	{
		$this->_comment = null;
		date_default_timezone_set('Europe/London');
		$this->_date = date('m/d/Y h:i:s a', time());
		$this->_nodeType = 'Comment';
		$this->_sID = null;
		$this->_pHID = null;
		$this->_eID = null;
		$this->_gID = null;
		$this->_uID = null;
		$this->_pID = null;
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
		$this->_cID = $commentId;
		return $commentId;
	}

	/** Function to delete a comment node given an ID.
	 * @access private
	 * @param cID
	 */
	public function deleteComment($cID)
	{
		$graph = new Graph();
		$succDelete = $graph->deleteNodeByID($cID);
		if (!$succDelete)
		{
			throw new Exception("Comment $cID could not be deleted.");
		}
		$this->_cID = null;
	}

	/**
	 * This method edits some of the properties of a comment in the GD by updating the current node in 
	 * the GD with information provided by the cArray which is the input to the editComment method
	 * @access public
	 * @param cArray
	 */
	public function editComment($cArray)
	{
		$graph = new Graph();
		$succEdit = $graph->editNodeProperties($cArray);
		if (!$succEdit)
		{
			throw new Exception("Comment could not be edited.");
		}
	}

	public function addCommentStatus($commentID, $statusID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->addConnection($commentID, $statusID, $connectionType);
		if (!$succ)
		{
			throw new Exception("New comment on status $statusID could not be added.");
		}
		$this->_sID = $statusID;
	}

	public function deleteCommentStatus($commentID, $statusID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $statusID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Latest comment on status $statusID could not be deleted.");
		}
		$this->_sID = null;
	}

	public function addCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->addConnection($commentID, $postID, $connectionType);
		if (!$succ)
		{
			throw new Exception("New comment on post $postID could not be added.");
		}
		$this->_pID = $postID;
	}

	public function deleteCommentPost($commentID, $postID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $postID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Latest comment on post $postID could not be deleted.");
		}
		$this->_pID = null;
	}

	public function addPhotoComment($commentID, $photoID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->addConnection($commentID, $photoID, $connectionType);
		if (!$succ)
		{
			throw new Exception("New comment on photo $photoID could not be added.");
		}
		$this->_pHID = $photoID;
	}

	public function deletePhotoComment($commentID, $photoID)
	{
		$graph = new Graph();
		$connectionType = 'POSTED_ON';
		$succ = $graph->deleteConnection($commentID, $photoID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Latest comment on photo $photoID could not be deleted.");
		}
		$this->_pHID = null;
	}

	public function connectComments($commentID, $commentID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
		$succ = $graph->addConnection($commentID, $commentID2, $connectionType);
		if (!$succ)
		{
			throw new Exception("New comment $newCommId could not be connected to the previous latest message $currentId.");
		}
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
		if ($nodeType == 'status')
		{
			$statusID = $nodeID;
			$succ = $this->deleteCommentStatus($currLatestId, $statusID);
			$succ = $this->addCommentStatus($newCommId, $statusID);
		}
		if ($nodeType == 'post')
		{
			$postID = $nodeID;
			$succ = $this->deleteCommentPost($currLatestId, $postID);
			$succ = $this->addCommentPost($newCommId, $postID);
		}

		if ($nodeType == 'photo')
		{
			$photoID = $nodeID;
			$succ = $this->deletePhotoComment($currLatestId, $photoID);
			$succ = $this->addPhotoComment($newCommId, $photoID);
		}
		$succ = $this->connectComments($newCommId, $currLatestId);
		$this->_latestCommID = $newCommId;
	}

	/**********************************************************/
	/** SETS and GETS *****************************/
	/**********************************************************/
	public function getComment()
	{
		return $this->_comment;
	}

	public function setComment($comment)
	{
		$this->_comment = $comment;
	}

	public function getCommentDate()
	{
		return $this->_date;
	}

	public function setCommentDate($date)
	{
		$this->_date = $date;
	}

	public function getCommentID()
	{
		return $this->_cID;
	}

	public function setCommentID($cID)
	{
		$this->_cID = $cID;
	}

	public function getCommentStatusID()
	{
		return $this->_sID;
	}

	public function setCommentStatusID($cID)
	{
		$this->_cID = $cID;
	}

	public function getCommentPhotoID()
	{
		return $this->_pHID;
	}

	public function setCommentPhotoID($phID)
	{
		$this->_pHID = $phID;
	}

	public function getCommentEventID()
	{
		return $this->_eID;
	}

	public function setCommentEventID($eID)
	{
		$this->_eID = $eID;
	}

	public function getCommentGroupID()
	{
		return $this->_gID;
	}

	public function setCommentGroupID($gID)
	{
		$this->_gID = $gID;
	}

	public function getCommentUserID()
	{
		return $this->_uID;
	}

	public function setCommentUserID($uID)
	{
		$this->_uID = $uID;
	}

	public function getCommentPostID()
	{
		return $this->_pID;
	}

	public function setCommentPostID($PID)
	{
		$this->_pID = $pID;
	}

}

?>