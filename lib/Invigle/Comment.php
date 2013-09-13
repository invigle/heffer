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
		$event['query'] = "CREATE (n:Comment {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $comment);

		//return the New Event ID.
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

	/**
	 * This method returns a comment
	 * @access public
	 * @return string
	 */
	public function getComment()
	{
		return $this->_comment;
	}

	/**
	 * This method sets a comment.
	 * @access public
	 * @param $comment (string)
	 * @return boolean
	 */
	public function setComment($comment)
	{
		$this->_comment = $comment;
	}

	/**
	 * This method returns the date of the comment
	 * @access public
	 * @return timestamp
	 */
	public function getCommentDate()
	{
		return $this->_date;
	}

	/**
	 * This method sets the date of a comment.
	 * @access public
	 * @param $date (timestamp)
	 * @return boolean
	 */
	public function setCommentDate($date)
	{
		$this->_date = $date;
	}

	/**
	 * This method returns the ID of the comment.
	 * @access public
	 * @return integer
	 */
	public function getCommentId()
	{
		return $this->_cID;
	}

	/**
	 * This method sets the ID of the comment.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentId($id)
	{
		$this->_cID = $id;
	}

	/**
	 * This method returns the ID of the status the comment was posted on.
	 * @access public
	 * @return integer
	 */
	public function getCommentStatusId()
	{
		return $this->_sID;
	}

	/**
	 * This method sets the ID of the status the comment was posted on.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentStatusId($id)
	{
		$this->_sID = $id;
	}

	/**
	 * This method returns the ID of the photo the comment was posted on.
	 * @access public
	 * @return integer
	 */
	public function getCommentPhotoId()
	{
		return $this->_pHID;
	}

	/**
	 * This method sets the ID of the photo the comment was posted on.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentPhotoId($id)
	{
		$this->_pHID = $id;
	}

	/**
	 * This method returns the ID of the event the comment was posted on its timeline.
	 * @access public
	 * @return integer
	 */
	public function getCommentEventId()
	{
		return $this->_eID;
	}

	/**
	 * This method sets the ID of the event the comment was posted on its timeline.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentEventId($id)
	{
		$this->_eID = $id;
	}

	/**
	 * This method returns the ID of the group the comment was posted on its timeline.
	 * @access public
	 * @return integer
	 */
	public function getCommentGroupId()
	{
		return $this->_gID;
	}

	/**
	 * This method sets the ID of the group the comment was posted on its timeline.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentGroupId($id)
	{
		$this->_gID = $id;
	}

	/**
	 * This method returns the ID of the user who posted the comment.
	 * @access public
	 * @return integer
	 */
	public function getCommentUserId()
	{
		return $this->_uID;
	}

	/**
	 * This method sets the ID of the user who posted the comment.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentUserId($id)
	{
		$this->_uID = $id;
	}

	/**
	 * This method returns the ID of the page who the comment was posted on its timeline.
	 * @access public
	 * @return integer
	 */
	public function getCommentPageId()
	{
		return $this->_pID;
	}

	/**
	 * This method sets the ID of the page who the comment was posted on its timeline.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentPageId($id)
	{
		$this->_pID = $id;
	}

}

?>