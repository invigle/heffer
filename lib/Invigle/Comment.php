<?php

namespace Invigle;

/**
 * @access public
 * @author GGM //Grant-Gavin-Manos
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
	 * @access private
	 * @param aDataArray
	 */
	public function createComment($aDataArray)
	{
		// Not yet implemented
	}

	/**
	 * @access private
	 * @param aCID
	 */
	public function deleteComment($aCID)
	{
		// Not yet implemented
	}

	/**
	 * @access private
	 * @param aDataArray
	 */
	public function editComment($aDataArray)
	{
		// Not yet implemented
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
    
    
    
    
    
    
    
}

?>