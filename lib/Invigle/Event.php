<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access private
 * @author Manos
 */
class Event
{
	private $_name;
	private $_date;
	private $_category;
	private $_description;
	private $_privacy;
	private $_institution;
	private $_isPaid;
	private $_paymentType;
	private $_location;
	private $_attendeeCount;
	private $_invitedCount;
	private $_followerCount;
	private $_eventType;
	private $_pHID;
	private $_profilePicID;
	private $_timestamp;
	private $_nodeType;
	private $_eID;
	private $_uID;
	private $_gID;

	/* The Class Constructor*/
	public function __construct()
	{
		$this->_name = null;
		$this->_date = null;
		$this->_category = null;
		$this->_description = null;
		$this->_privacy = null;
		$this->_institution = null;
		$this->_isPaid = null;
		$this->_paymentType = null;
		$this->_location = null;
		$this->_attendeeCount = null;
		$this->_invitedCount = null;
		$this->_followerCount = null;
		$this->_eventType = null;
		$this->_pHID = null;
		$this->_profilePicID = null;
		date_default_timezone_set('Europe/London');
		$this->_timestamp = date('m/d/Y h:i:s a', time());
		$this->_nodeType = 'Event';
		$this->_eID = null;
		$this->_uID = null;
		$this->_gID = null;
	}

	/**
	 * This method takes as input an array with all the information of an event and 
	 * adds this event to the GD as an 'event node'.
	 * @access public
	 * @param eArray
	 * @return integer
	 */
	public function addEvent($eArray)
	{
	    $graphModule = new Graph();
        
        $newEventArray = array(
            'name'=>$eArray['name'],
            'description'=>$eArray['description'],
            'location'=>$eArray['location'],
            'categories'=>$eArray['categories'],
            'type'=>$eArray['type'],
            'date'=>"$eArray[date_day]-$eArray[date_month]-$eArray[date_year]",
            'privacy'=>$eArray['privacy'],
            'timestamp'=>time(),
            'profilePicID'=>'',
            'INID'=>'',
            'LID'=>'',
            'attendeeCount'=>'0',
            'invitedCount'=>'0',
        );
        
        if(isset($eArray['isPaid'])){
            $newEventArray['isPaid'] = $eArray['isPaid'];
            $newEventArray['paymentType'] = $eArray['paymentType'];
        }
        
        if($eArray['createEventAs'] === "user"){
            $newEventArray['ownerType'] = "user";
            $newEventArray['OwnerID'] = $_SESSION['uid'];
        }
        
        $eventId = $graphModule->createNode('Event', $newEventArray);
		
        //Add a connection from the creator to the event.
        if($eArray['createEventAs'] === "user"){
            $creatorId = $_SESSION['uid'];
            if(isset($eArray['timeline'])){
                //Add to users Timeline
                $createActionProperties = array(
                    'actionType'=>'newEvent',
                    'timestamp'=>time(),
                    'uid'=>$eventId,
                );
                
                $newEventActionId = $graphModule->createNode('Action', $createActionProperties);
                
                
                $userModule = new User();
                $userModule->updateUserTimeline($_SESSION['uid'], $newEventActionId);
            }
        }
        
        $graphModule->addConnection($creatorId, $eventId, 'organiserOf');
        
        $this->_eID = $eventId;
		return $eventId;
	}

    /** Function to return an array of a users invites.
     *
     */
    public function userInvites($uID)
    {
        $graphModule = new Graph();
		$rels = $graphModule->neo4japi('node/'.$uID.'/relationships/out/invitedTo', 'GET');

		$i = 0;
		foreach ($rels as $ev)
		{
			$st = explode('/', $ev['end']);            
            $eventData = $graphModule->selectNodeById(end($st));
            $event = $eventData['data'][0][0]['data'];

			$rtn[$i]['eventid'] = end($st);
			$rtn[$i]['name'] = $event['name'];
            $rtn[$i]['date'] = $event['date'];
            
            unset($event);
			$i++;
		}

		if (isset($rtn))
		{
			return $rtn;
		}
    }
    
    /**
     * Function to check follower status
     */
    public function checkFollowStatus($follower, $followee)
	{
		$graphModule = new Graph();
		$rels = $graphModule->neo4japi('node/'.$follower.'/relationships/out/followerOf', 'GET');
		foreach ($rels as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $followee)
			{
				return true;
				break;
			}
		}
		return false;
	}
    
	public function getNumberOfFollowers($uID)
	{
		$graphModule = new Graph();
		$apiCall = $graphModule->selectNodeById($uID);
		$user = $apiCall['data'][0][0]['data'];

		return $user['followerCount'];
	}

	public function setNumberOfFollowers($uID, $count)
	{
		$graphModule = new Graph();
		$this->_followerCount = $count;
        
        $properties['followerCount'] = $count;
        $graphModule->updateNodeById($uID, $properties);
	}

	public function increaseFollowersCount($uID)
	{
		$followers = $this->getNumberOfFollowers($uID) + 1;
		$this->setNumberOfFollowers($uID, $followers);
	}
    
    /**
     * Function to follow an event.
     */
    public function followEvent($userId, $eventId)
    {
		$graphModule = new Graph();

		//Add the relationship between follower and followee.
		$api = $graphModule->addConnection($userId, $eventId, 'followerOf');
        
        $createProperties = array(
            'actionType'=>'followerOf',
            'timestamp'=>time(),
            'uid'=>$eventId,
        );
        
        $actionId = $graphModule->createNode('Action', $createProperties);
                
		//Add a relationship from follower to action node.
        $this->updateUserTimeline($userId, $actionId);

		//Update the Users last action timestamp.
		$this->updateUserTimestamp($userId);

		//Update Number of Followers
		$this->increaseFollowersCount($eventId);
    }

	/** Function to delete an event node given an ID.
	 * @access private
	 * @param eID
	 */
	public function deleteEvent($eID)
	{
		$graph = new Graph();
		$succ = $graph->deleteNodeByID($eID);
		if (!$succ)
		{
			throw new Exception("Event $eID could not be deleted.");
		}
		$this->_eID = null;
	}

	/**
	 * This method edits some of the properties of an event in the GD by updating the current node in 
	 * the GD with information provided by the eArray which is the input to the editEvent method
	 * @access public
	 * @param eArray
	 * @return boolean
	 */
	public function editEvent($eArray)
	{
		$graph = new Graph();
		$succ = $graph->editNodeProperties($eArray);
		return $succ;
	}

	/**
	 * This method takes as inputs a photo ID, the ID of an event and adds a RELATED_TO edge to neo4j.
	 * @access public
	 * @param phID, eID
	 */
	public function addEventPhoto($phID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'RELATED_TO';
		$succ = $graph->addConnection($phID, $eID, $connectionType);
		if (!$succ)
		{
			throw new Exception("New photo $phID could not be added to event $eID.");
		}
		$this->_pHID = $phID;
	}

	/**
	 * This method takes as inputs a photo ID, the ID of an event and deletes a RELATED_TO edge from neo4j.
	 * @access public
	 * @param phID, eID
	 */
	public function deleteEventPhoto($phID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'RELATED_TO';
		$succ = $graph->deleteConnection($phID, $eID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Photo $phID could not be deleted from event $phID.");
		}
		$this->_phID = null;
	}

	/**
	 * This method takes as inputs a event ID and a location ID and adds a LOCATED_AT edge to neo4j.
	 * @access public
	 * @param eID, locID
	 */
	public function addEventLocation($eID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->addConnection($eID, $locID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Location $locID could not be added to event $eID.");
		}
		$this->_location = $locID;
	}

	/**
	 * This method takes as inputs an event ID and a location ID and deletes a LOCATED_AT edge from neo4j.
	 * @access public
	 * @param eID, locID
	 */
	public function deleteEventLocation($eID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->deleteConnection($eID, $locID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Location $locID could not be deleted from event $phID.");
		}
		$this->_location = null;
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
	 * This method takes as inputs a user ID and a event ID and adds a FOLLOWER_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventFollower($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventFollower($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a event ID and adds a ORGANISER_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventOrganiser($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventOrganiser($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function changeEventOrganiser($uID, $uID2, $pID)
	{
		$graph = new Graph();
		$succ = $this->deleteEventOrganiser($uID, $pID);
		if ($succ == 1)
			$succ2 = $this->addEventOrganiser($uID2, $pID);
	}

	/**
	 * This method takes as inputs a user ID and a event ID and adds a ATTENDEE_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventAttendee($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventAttendee($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and an event ID and adds an INVITED_TO edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addUserEventInvitation($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'INVITED_TO';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	/**********************************************************/
	/** SETS and GETS *****************************/
	/**********************************************************/
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