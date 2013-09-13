<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access public
 * @author Grant
 */
class Photo {
	private $_photoData;
	private $_timestamp;
	private $_pHID;
	private $_eID;
	private $_uID;
	private $_tagArray;
	private $_pID;


    /**
	 * This method takes as input an array with all the information of a photo and 
	 * adds this photo to the GD as a 'photo node'.
	 * @access public
	 * @param phArray
	 * @return integer
	 */
	public function addLocation($phArray)
	{
		//Create the new photo in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($phArray as $key => $value)
		{
			$queryString .= "$key: \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$photo['query'] = "CREATE (n:Photo {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $photo);

		//return the new photo ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$photoId = end($bit);
		return $photoId;
	}
    
    /** Function to delete a photo node given an ID.
	 * @access private
	 * @param phID
	 * @return boolean
	 */
	public function deletePhoto($phID)
	{
		$graph = new Graph();
		$succDelete = $graph->deleteNodeByID($phID);
		return $succDelete;
	}

	/**
	 * @access public
	 * @param aPHID
	 * @param aUID
	 */
	public function addTag($aPHID, $aUID) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aPHID
	 * @param aUID
	 */
	public function deleteTag($aPHID, $aUID) {
		// Not yet implemented
	}
    
    /**
	 * This method returns the metadata of the photo.
	 * @access public
	 * @return string
	 */
	public function getPhotoData()
	{
		return $this->_photoData;
	}

	/**
	 * This method sets the group's slogan.
	 * @access public
	 * @param data (string)
	 * @return boolean
	 */
	public function setPhotoData($data)
	{
		$this->_photoData = $data;
	}

    /**
	 * This method returns the timestamp in UTC indicating when photo was uploaded to Invigle.
	 * @access public
	 * @return date
	 */
	public function getPhotoTimestamp()
	{
		return $this->_timestamp;
	}

	/**
	 * This method sets the timestamp in UTC that photo was uploaded to Invgile.
	 * @access public
	 * @param timestamp (date)
	 * @return boolean
	 */
	public function setPhotoTimestamp($timestamp)
	{
		$this->_timestamp = $timestamp;
	}  
    
    /**
	 * This method returns the ID of the event that the photo was taken at.
	 * @access public
	 * @return integer
	 */
	public function getPhotoEventId()
	{
		return $this->_eID;
	}

	/**
	 * This method sets the ID of the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setPhotoEventId($id)
	{
		$this->_eID = $id;
	} 
    
    /**
	 * This method returns the ID of the user who uploaded the photo.
	 * @access public
	 * @return integer
	 */
	public function getPhotoUploaderId()
	{
		return $this->_uID;
	}

	/**
	 * This method sets the ID of the user who uploaded the photo.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setPhotoUploaderId($id)
	{
		$this->_uID = $id;
	} 

    /**
	 * This method returns an array with all the tags on that photo.
	 * @access public
	 * @return integer
	 */
	public function getTagArray()
	{
		return $this->_tagArray;
	}

	/**
	 * This method sets the array of tags of this photo. 
     * It can be used when a tag is added to update the _tagArray
	 * @access public
	 * @param array (array)
	 * @return boolean
	 */
	public function setTagArray($array)
	{
		$this->_tagArray = $array;
	}  
    
    /**
	 * This method returns the ID of the page the photo was uploaded to.
	 * @access public
	 * @return integer
	 */
	public function getPhotoPageId()
	{
		return $this->_uID;
	}

	/**
	 * This method sets the ID of the page the photo was uploaded to.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setPhotoPageId($id)
	{
		$this->_pID = $id;
	}    
}
?>