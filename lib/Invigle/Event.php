<?php

namespace Invigle;

/**
 * @access private
 *
 */
class Event
{
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
    private $_followerCount;
	private $_eventType;
	private $_pID;
	private $_profilePicID;
	private $_timestamp;

	/**
	 * This method takes as input an array with all the information of an event and adds this event to the GD as an 'event node'.
	 * @access public
	 * @param aMetaArray
	 */
	public function addEvent($aMetaArray)
	{
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of an event and deletes the node that represent this event from the GD.
	 * @access public
	 * @param aEID
	 * @return boolean
	 */
	public function deleteEvent($aEID)
	{
		// Not yet implemented
	}

	/**
	 * This method edits some of the properties of an event in the GD by updating the current node in the GD with information provided by the metaArray which is the input to the editEvent method
	 * @access public
	 * @param aMetaArray
	 */
	public function editEvent($aMetaArray)
	{
		// Not yet implemented
	}

	/**
	 * This method takes as input the date of the event and returns the forecasted weather for that day.
	 * @access public
	 * @param aDate
	 */
	public function getWeather($aDate)
	{
		// Not yet implemented
	}

	/**
	 * This method gets the user current location and the location of the event and return a route to the event from the current location by using an google maps API.
	 * @access public
	 * @param aFromLocation
	 * @param aToLocation
	 */
	public function getDirections($aFromLocation, $aToLocation)
	{
		// Not yet implemented
	}

	/**
	 * This method returns the name of the event.
	 * @access public
	 * @return string
	 */
	public function getEventName()
	{
		return $this->_name;
	}

	/**
	 * This method sets the name of the event.
	 * @access public
	 * @param name (string)
	 * @return boolean
	 */
	public function setEventName($name)
	{
		$this->_name = $name;
	}

	/**
	 * This method returns the date of the event.
	 * @access public
	 * @return date
	 */
	public function getEventDate()
	{
		return $this->_date;
	}

	/**
	 * This method sets the date of the event.
	 * @access public
	 * @param date (date)
	 * @return boolean
	 */
	public function setEventDate($date)
	{
		$this->_date = $date;
	}

	/**
	 * This method returns the event category (public or private).
	 * @access public
	 * @return string
	 */
	public function getEventCategory()
	{
		return $this->_category;
	}

	/**
	 * This method sets the event category (public or private).
	 * @access public
	 * @param category (string))
	 * @return boolean
	 */
	public function setEventCategory($category)
	{
		$this->_category = $category;
	}

	/**
	 * This method returns the description of the event.
	 * @access public
	 * @return string
	 */
	public function getEventDescription()
	{
		return $this->_description;
	}

	/**
	 * This method sets the description of the event.
	 * @access public
	 * @param description (string)
	 * @return boolean
	 */
	public function setEventDescription($description)
	{
		$this->_description = $description;
	}

	/**
	 * This method returns 1 if the event if private, 0 otherwise.
	 * @access public
	 * @return boolean 
	 */
	public function getEventPrivacy()
	{
		return $this->_privacy;
	}

	/**
	 * This method sets the value _privacy to 1 if the event is private, 0 otherwise.
	 * @access public
	 * @param privacy (boolean)
	 * @return boolean
	 */
	public function setEventPrivacy($privacy)
	{
		$this->_privacy = $privacy;
	}

	/**
	 * This method returns the ID of the event.
	 * @access public
	 * @return integer
	 */
	public function getEventId()
	{
		return $this->_eID;
	}

	/**
	 * This method sets the ID of the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setEventId($id)
	{
		$this->_eID = $id;
	}

	/**
	 * This method returns the institution that organises the event.
	 * @access public
	 * @return string
	 */
	public function getEventInstitution()
	{
		return $this->_institution;
	}
    
	/**
	 * This method sets the institution of the event.
	 * @access public
	 * @param institution (string)
	 * @return boolean
	 */
	public function setEventInstitution($institution)
	{
		$this->_institution = $institution;
	}

	/**
	 * This method returns the ID of the organiser of the event.
	 * @access public
	 * @return integer
	 */
	public function getEventOrganiserId()
	{
		return $this->_uID;
	}

	/**
	 * This method sets the ID of the organiser of the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setEventOrganiserId($id)
	{
		$this->_uID = $id;
	}

	/**
	 * This method returns the ID of the group which organises the event.
	 * @access public
	 * @return integer
	 */
	public function getEventGroupId()
	{
		return $this->_gID;
	}

	/**
	 * This method sets the ID of the group which organises the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setEventGroupId($id)
	{
		$this->_gID = $id;
	}

	/**
	 * This method returns 1 if the event charges an attendance fee, 0 otherwise.
	 * @access public
	 * @return boolean
	 */
	public function getEventPaidState()
	{
		return $this->_isPaid;
	}

	/**
	 * This method sets the value to 1 if the event charges an attendance fee, 0 otherwise.
	 * @access public
	 * @param paid (boolean)
	 * @return boolean
	 */
	public function setEventPaidState($paid)
	{
		$this->_isPaid = $paid;
	}

	/**
	 * This method returns the payment type the event accepts.
	 * @access public
	 * @return integer
	 */
	public function getEventPaymentType()
	{
		return $this->_paymentType;
	}

	/**
	 * This method sets the payment type of the event.
	 * @access public
	 * @param payment (integer)
	 * @return boolean
	 */
	public function setEventPaymentType($payment)
	{
		$this->_paymentType = $payment;
	}

	/**
	 * This method returns location of the event.
	 * @access public
	 * @return string
	 */
	public function getEventLocation()
	{
		return $this->_location;
	}

	/**
	 * This method sets the location of the event.
	 * @access public
	 * @param location (string)
	 * @return boolean
	 */
	public function setEventLocation($location)
	{
		$this->_location = $location;
	}

	/**
	 * This method returns the number of attendees of the event.
	 * @access public
	 * @return integer
	 */
	public function getNumberOfEventAttendees()
	{
		return $this->$_attendeeCount;
	}

	/**
	 * This method sets the number of attendees of the event.
	 * @access public
	 * @param attendees (integer)
	 * @return boolean
	 */
	public function setNumberOfEventAttendees($attendees)
	{
		$this->_location = $attendees;
	}

	/**
	 * This method returns users who have been invited to the event.
	 * @access public
	 * @return integer
	 */
	public function getNumberOfEventInvitees()
	{
		return $this->_invitedCount;
	}

	/**
	 * This method sets the number of users who have been invited to the event.
	 * @access public
	 * @param invitees (integer)
	 * @return boolean
	 */
	public function setNumberOfEventInvitees($invitees)
	{
		$this->_invitedCount = $invitees;
	}

	/**
	 * This method returns the type of the event.
	 * @access public
	 * @return string
	 */
	public function getEventType()
	{
		return $this->_eventType;
	}

	/**
	 * This method sets the type of the event.
	 * @access public
	 * @param type (string)
	 * @return boolean
	 */
	public function setEventType($type)
	{
		$this->_eventType = $type;
	}

	/**
	 * This method returns the type of the event.
	 * @access public
	 * @return integer
	 */
	public function getEventPage()
	{
		return $this->_pID;
	}

	/**
	 * This method sets the ID of the page of the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setEventPage($id)
	{
		$this->_pID = $id;
	}

	/**
	 * This method returns the ID of the profile picture of the event.
	 * @access public
	 * @return integer
	 */
	public function getEventProfPicId()
	{
		return $this->_profilePicID;
	}

	/**
	 * This method sets the ID of the profile picture of the event.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setEventProfPicId($id)
	{
		$this->_profilePicID = $id;
	}

	/**
	 * This method returns the timestamp in UTC indicating when the event was created.
	 * @access public
	 * @return date
	 */
	public function getEventTimestamp()
	{
		return $this->_timestamp;
	}

	/**
	 * This method sets the timestamp in UTC that the event was created at.
	 * @access public
	 * @param timestamp (date)
	 * @return boolean
	 */
	public function setEventTimestamp($timestamp)
	{
		$this->_timestamp = $timestamp;
	}
    
    /**
	 * This method returns the number of followers of the event.
	 * @return integer
	 */
	public function getNumberOfEventFollowers()
	{
		return $this->$_followerCount;
	}

	/**
	 * This method sets the number of followers of the event.
	 * @access public
	 * @param count (integer)
	 * @return boolean
	 */
	public function setNumberOfEventFollowers($count)
	{
		$this->_followerCount = $count;
	}
    
    
    
}

?>