<?php

namespace Invigle;

use Invigle\Graph;

/**
 * @access public
 * @author Manos
 */
class Location
{
	private $_lID;
	private $_name;
	private $_coordinates;
	private $_postCode;

	/**
	 * This method takes as input an array with all the information of a location and 
	 * adds this location to the GD as a 'location node'.
	 * @access public
	 * @param lArray
	 * @return integer
	 */
	public function addLocation($lArray)
	{
		//Create the new location in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($lArray as $key => $value)
		{
			$queryString .= "$key: \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$location['query'] = "CREATE (n:Location {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $location);

		//return the new location ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$locationId = end($bit);
		return $locationId;
	}

	/** Function to delete a location node given an ID.
	 * @access private
	 * @param lID
	 * @return boolean
	 */
	public function deleteLocation($lID)
	{
		$graph = new Graph();
		$succDelete = $graph->deleteNodeByID($lID);
		return $succDelete;
	}

	/**
	 * This method edits some of the properties of a location in the GD by updating the current node in 
	 * the GD with information provided by the lArray which is the input to the editComment method
	 * @access public
	 * @param lArray
	 * @return boolean
	 */
	public function editLocation($lArray)
	{
		$graph = new Graph();
		$succEdit = $graph->editNodeProperties($lArray);
		return $succEdit;
	}

	/**
	 * This method returns the ID of the location.
	 * @access public
	 * @return integer
	 */
	public function getLocationId()
	{
		return $this->_lID;
	}

	/**
	 * This method sets the ID of the location which organises the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setLocationId($id)
	{
		$this->_lID = $id;
	}

	/**
	 * This method returns the name of the location.
	 * @access public
	 * @return string
	 */
	public function getLocationName()
	{
		return $this->_name;
	}

	/**
	 * This method sets the name of the location.
	 * @access public
	 * @param name (string)
	 * @return boolean
	 */
	public function setLocationName($name)
	{
		$this->_name = $name;
	}

	/**
	 * This method returns the coordinates of the location.
	 * @access public
	 * @return degrees
	 */
	public function getLocationCoord()
	{
		return $this->_coordinates;
	}

	/**
	 * This method sets the coordinates of the location.
	 * @access public
	 * @param degrees 
	 * @return boolean
	 */
	public function setLocationCoord($degrees)
	{
		$this->_coordinates = $degrees;
	}

    /**
	 * This method returns the postcode of the location.
	 * @access public
	 * @return string
	 */
	public function getLocationPostcode()
	{
		return $this->_postCode;
	}

	/**
	 * This method sets the postcode of the location.
	 * @access public
	 * @param postcode (string)
	 * @return boolean
	 */
	public function setLocationPostcode($postcode)
	{
		$this->_postCode = $postcode;
	}
}

?>