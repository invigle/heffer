<?php
/**
 * test
 * @access public
 * @author Grant
 */
class Event {
	public $_name;
	public $_date;
	public $_cateogry;
	public $_description;
	public $_privacy;
	public $_eID;
	public $_institution;
	public $_uID;
	public $_gID;
	public $_isPaid;
	public $_paymentType;
	public $_location;
	public $_attendeeCount;
	public $_invitedCount;
	public $_eventType;
	public $_pID;
	public $_profilePicID;
	public $_timestamp;

	/**
	 * This method takes as input an array with all the information of an event and adds this event to the GD as an 'event node'.
	 * @access public
	 * @param aMetaArray
	 */
	public function addEvent($aMetaArray) {
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of an event and deletes the node that represent this event from the GD.
	 * @access public
	 * @param aEID
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function deleteEvent($aEID) {
		// Not yet implemented
	}

	/**
	 * This method edits some of the properties of an event in the GD by updating the current node in the GD with information provided by the metaArray which is the input to the editEvent method
	 * @access public
	 * @param aMetaArray
	 */
	public function editEvent($aMetaArray) {
		// Not yet implemented
	}

	/**
	 * This method takes as input the date of the event and returns the forecasted weather for that day.
	 * @access public
	 * @param aDate
	 */
	public function getWeather($aDate) {
		// Not yet implemented
	}

	/**
	 * This method gets the user current location and the location of the event and return a route to the event from the current location by using an google maps API.
	 * @access public
	 * @param aFromLocation
	 * @param aToLocation
	 */
	public function getDirections($aFromLocation, $aToLocation) {
		// Not yet implemented
	}
}
?>