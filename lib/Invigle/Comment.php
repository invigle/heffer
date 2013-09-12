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
	 * @return date
	 */
	public function getCommentDate()
	{
		return $this->_date;
	}
    
    /**
	 * This method sets the date of a comment.
	 * @access public
	 * @param $comment (string)
	 * @return boolean
	 */
	public function setCommentDate($date)
	{
		$this->_date = $date;
	}

    
    // $_date;$_cID;$_sID; $_pHID;$_eID;$_gID;$_uID;$_pID;
    
    
    
    
    
}

?>