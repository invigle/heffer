<?php

namespace Invigle;

/**
* @access private
* 
*/
class Event {
	private $_name;
	private $_date;
	private $_category;
	private $_description;
	private $_privacy;
	private $_eID;
	private $_institution;
	private $_uID;
	private $_gID;
	private $_isPaid;
	private $_paymentType;
	private $_location;
	private $_attendeeCount;
	private $_invitedCount;
	private $_eventType;
	private $_pID;
	private $_profilePicID;
	private $_timestamp;

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
	*/
	public function deleteEvent($aEID) {
		// Not yet implemented
	}

	/**
	* This method edits some of the properties of an event in the GD by updating the current node in the GD with information provided by the metaArray which is the input 	to the editEvent method
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

	/**
	* This method returns the name of the event.
	* @access public
	* @return string
	*/
	public function getEventName(){
		return $this->_name;
	}

	/**
	* This method returns the date of the event.
	* @access public
	* @return date
	*/
	public function getEventDate(){
		return $this->_date;
	}

	/**
	* This method returns the category of the event.
	* @access public
	* @return string
	*/
	public function getEventCategory(){
		return $this->_category;
	}

	/**
	* This method returns the description of the event.
	* @access public
	* @return string
	*/
	public function getEventDescription(){
		return $this->_description;
	}

	/**
	* This method returns the privacy of the event.
	* @access public
	* @return boolean -- public = 0 /private = 1
	*/
	public function getEventPrivacy(){
		return $this->_privacy;
	}

	/**
	* This method returns the ID of the event.
	* @access public
	* @return boolean -- public/private
	*/
	public function getEventId(){
		return $this->_eID;
	}

	/**
	* This method returns the institution that organises the event.
	* @access public
	* @return string
	*/
	public function getEventInstitution(){
		return $this->_institution;
	}

	/**
	* This method returns the ID of the organiser of the event.
	* @access public
	* @return integer
	*/
	public function getEventOrganiserId(){
		return $this->_uID;
	}

	/**
	* This method returns the ID of the group which organises the event.
	* @access public
	* @return integer
	*/
	public function getEventGroupId(){
		return $this->_gID;
	}

	/**
	* This method returns 0 if the event is paid, 1 otherwise.
	* @access public
	* @return boolean
	*/
	public function getEventPaidState(){
		return $this->_isPaid;
	}

	/**
	* This method returns the payment type the event accepts.
	* @access public
	* @return integer
	*/
	public function getEventPaymentType(){
		return $this->_paymentType;
	}

	/**
	* This method returns location of the event. 
	* @access public
	* @return string
	*/
	public function getEventLocation(){
		return $this->_location;
	}

	/**
	* This method returns the number of attendees of the event. 
	* @access public
	* @return integer
	*/
	public function getNumberOfEventAttendees(){
		return $this->$_attendeeCount;
	}

	/**
	* This method returns the number of invitees of the event. 
	* @access public
	* @return integer
	*/
	public function getNumberOfEventInvitees(){
		return $this->_invitedCount;
	}

	/**
	* This method returns the type of the event. 
	* @access public
	* @return string
	*/
	public function getEventType(){
		return $this->_eventType;
	}

	/**
	* This method returns the type of the event. 
	* @access public
	* @return integer
	*/
	public function getEventPage(){
		return $this->_pID;
	}

	/**
	* This method returns the ID of the profile picture of the event.  
	* @access public
	* @return integer
	*/
	public function getEventProfPicId(){
		return $this->_profilePicID;
	}

	/**
	* This method returns the timestamp in UTC indicating when the event was created.
	* @access public
	* @return date
	*/
	public function getEventTimestamp(){
		return $this->_timestamp;
	}
}


?>