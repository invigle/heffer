<?php

namespace Invigle;

use Invigle\Graph;

/**
 * @access public
 * @author Manos
 */
class Location
{
    private $_locID;
    private $_name;
    private $_postCode;
    private $_latitude;
    private $_longitude;

    /* The Class Constructor*/

    public function __construct()
    {
        $this->_locID = null;
        $this->_name = null;
        $this->_postCode = null;
        $this->_latitude = null;
        $this->_longitude = null;
    }


    /* This method takes as input an array with all the information of a location and
    * adds this location to the GD as a page node.
    * @access public
    * @param locArray
    * @return boolean
    */
    public function addLocation($locArray)
    {
        $graphModule = new Graph();

        $newLocationArray = array(
            'locID' => '',
            'name' => $locArray['name'],
            'postCode' => $locArray['postCode'],
            'latitude' => $locArray['latitude'],
            'longitude' => $locArray['longitude'],
        );

        // Creating a location node.
        $locationId = $graphModule->createNode('Location', $newLocationArray);
        $this->_locID = $locationId;
    }


    /* This method deletes a location node given an ID.
    * @access public
    * @param locID
    * @return boolean
    */
    public function deleteLocation($locID)
    {
        $graphModule = new Graph();
        $this->_locID = $locID;
        if (!filter_var($this->_locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule->deleteNodeByID($this->_locID);
            $this->setLocationId($locID, null);
        }
    }


    /**
     * This method edits some of the properties of a location in the GD by updating the current node in
     * the GD with information provided by the locArray.
     * @access public
     * @param locArray
     * @return boolean
     */
    public function editLocation($locArray)
    {
        $graphModule = new Graph();

        $newLocArray = array(
            'name' => $locArray['name'],
            'postcode' => $locArray['postcode'],
            'latitude' => $locArray['latitude'],
            'longitude' => $locArray['longitude'],
        );

        $graphModule->editNodeProperties($newLocArray);
    }


    /**
     * This method sets the name of the location.
     * @access public
     * @param locID
     * @param location
     * @return boolean
     */
    public function setLocationName($locID, $location)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_name = $location;
            $update['name'] = $location;
            $graphModule->updateNodeById($locID, $update);
        }
    }


    /**
     * This method returns the name of a location.
     * @access public
     * @param locID
     * @return integer
     */
    public function getLocationName($locID)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($locID);
            $location = $apiCall['data'][0][0]['data'];
            return $location['locID'];
        }
    }


    /**
     * This method sets the postcode of a location.
     * @access public
     * @param locID
     * @param postcode
     * @return boolean
     */
    public function setLocationPostcode($locID, $postcode)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_postCode = $postcode;
            $update['postCode'] = $postcode;
            $graphModule->updateNodeById($locID, $update);
        }
    }


    /**
     * This method returns the postcode of a location.
     * @access public
     * @param locID
     * @return integer
     */
    public function getLocationPostcode($locID)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($locID);
            $postcode = $apiCall['data'][0][0]['data'];
            return $postcode['postCode'];
        }
    }


    /**
     * This method sets the latitude of a location.
     * @access public
     * @param locID
     * @param latitude
     * @return boolean
     */
    public function setLocationLatitude($locID, $latitude)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_latitude = $latitude;
            $update['latitude'] = $latitude;
            $graphModule->updateNodeById($locID, $update);
        }
    }


    /**
     * This method returns the latitude of a location.
     * @access public
     * @param locID
     * @return integer
     */
    public function getLocationLatitude($locID)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($locID);
            $latitude = $apiCall['data'][0][0]['data'];
            return $latitude['latitude'];
        }
    }


    /**
     * This method sets the longitude of a location.
     * @access public
     * @param locID
     * @param longtitude
     * @return boolean
     */
    public function setLocationLongitude($locID, $longitude)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_longitude = $longitude;
            $update['longitude'] = $longitude;
            $graphModule->updateNodeById($locID, $update);
        }
    }


    /**
     * This method returns the longitude of a location.
     * @access public
     * @param locID
     * @return integer
     */
    public function getLocationLongitude($locID)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($locID);
            $longitude = $apiCall['data'][0][0]['data'];
            return $longitude['longitude'];
        }
    }

    /* This method sets the ID of a location.
    * @access public
    * @param locID
    * @param id
    * @return boolean
    */
    public function setLocationId($locID, $id)
    {
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_locID = $id;
            $update['id'] = $id;
            $graphModule->updateNodeById($locID, $update);
        }
    }
}

?>