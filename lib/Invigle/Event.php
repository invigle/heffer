<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access private
 * @authors GH, MP
 */
class Event
{
    private $_name;
    private $_description;
    private $_location;
    private $_category;
    private $_date;
    private $_privacy;
    private $_institution;
    private $_isPaid;
    private $_paymentType;
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
    private $_pID;
    private $_organiser;


    /* The Class Constructor*/
    public function __construct()
    {
        $this->_name = null;
        $this->_description = null;
        $this->_location = null;
        $this->_date = null;
        $this->_category = null;
        $this->_description = null;
        $this->_privacy = null;
        $this->_institution = null;
        $this->_isPaid = null;
        $this->_paymentType = null;
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
        $this->_organiser = null;
    }


    /**
     * This method adds an event node to the GD.
     * @access public
     * @param eArray
     * @return integer
     */
    public function addEvent($eArray)
    {
        $graphModule = new Graph();

        $newEventArray = array(
            'name' => $eArray['name'],
            'description' => $eArray['description'],
            'location' => $eArray['location'],
            'categories' => $eArray['categories'],
            'type' => $eArray['type'],
            'date' => "$eArray[date_day]-$eArray[date_month]-$eArray[date_year]",
            'privacy' => $eArray['privacy'],
            'timestamp' => time(),
            'profilePicID' => '',
            'INID' => '',
            'LID' => '',
            'attendeeCount' => '0',
            'invitedCount' => '0',
        );

        // If the event is paid, the array of the new event is populated
        // with the field isPaid and the payment type.
        if (isset($eArray['isPaid'])) {
            $newEventArray['isPaid'] = $eArray['isPaid'];
            $newEventArray['paymentType'] = $eArray['paymentType'];
        }

        // The array of the new event gets the creator of the event and her/his ID.
        if ($eArray['createEventAs'] === "user") {
            $newEventArray['ownerType'] = "user";
            $newEventArray['OwnerID'] = $_SESSION['uid'];
        }

        // Creating the event node.
        $eventId = $graphModule->createNode('Event', $newEventArray);

        // todo: do we need the following or all have been already assigned by the above?
        // $this->setEventName($eventId, $eArray['name']);
        // $this->setEventDate($eventId, $eArray['description']);
        // $this->setEventLocation($eventId, $eArray['location']);
        // $this->setEventCategory($eventId, $eArray['categories']);
        // $this->setEventType($eventId, $eArray['type']);
        // $this->setEventDate($eventId, "$eArray[date_day]-$eArray[date_month]-$eArray[date_year]");
        // $this->setEventPrivacy($eventId, $eArray['privacy']);
        // $this->setEventTimestamp($eventId, time());
        // $this->setEventProfPicId($eventId, '');
        // $this->setNumberOfEventAttendees($eventId, '0');
        // $this->setNumberOfEventInvitees($eventId, '0');
        // $this->setNumberOfEventFollowers($eventId, '0');
        // $this->setEventPaidState($eventId, $eArray['isPaid']);
        // $this->setEventPaymentType($eventId, $eArray['paymentType']);

        if ($eArray['createEventAs'] === "user") {
            // Get the ID of the creator of the event.
            $creatorId = $_SESSION['uid'];
            // Set the properties of the createActionProperties array.
            if (isset($eArray['timeline'])) {
                $createActionProperties = array(
                    'actionType' => 'newEvent',
                    'timestamp' => time(),
                    'uid' => $eventId,
                );

                // Create the action node.
                $newEventActionId = $graphModule->createNode('Action', $createActionProperties);

                // Create a new user (the creator of the event) in order to update his/her timeline.
                $userModule = new User();

                // Update the user's timeline by connecting the ID of the creator with the ID of the new action which
                // shows the creation of a new event.
                $userModule->updateUserTimeline($_SESSION['uid'], $newEventActionId);
            }
            $this->_eID = $eventId;
            // Add a connection from the creator to the event.
            $graphModule->addConnection($creatorId, $this->_eID, 'organiserOf');
        }
    }


    /**
     * This method edits some of the properties of an event in the
     * GD by updating the current node with information provided by the eArray.
     * @access public
     * @param eArray
     * @return boolean
     */
    public function editEvent($eArray)
    {
        $graphModule = new Graph();

        $newEventArray = array(
            'name' => $eArray['name'],
            'description' => $eArray['description'],
            'location' => $eArray['location'],
            'categories' => $eArray['categories'],
            'type' => $eArray['type'],
            'date' => $eArray['date'],
            'privacy' => $eArray['privacy'],
            'timestamp' => time(),
            'profilePicID' => $eArray['profilePicID'],
            'INID' => $eArray['INID'],
            'LID' => $eArray['LID'],
            'attendeeCount' => $eArray['attendeeCount'],
            'invitedCount' => $eArray['invitedCount'],
            'isPaid' => $eArray['isPaid'],
            'paymentType' => $eArray['paymentType'],
        );
        $graphModule->editNodeProperties($newEventArray);
    }


    /* This method deletes an event node.
     * @access private
     * @param eID
     */
    public function deleteEvent($eID)
    {
        $graphModule = new Graph();
        $this->_eID = $eID;
        if (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule->deleteNodeByID($this->_eID);

        }
    }


    /**
     * photo ---relatedTo---> event
     * @access public
     * @param phID
     * @param eID
     * @return boolean
     */
    public function addEventPhoto($phID, $eID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->addConnection($this->_pHID, $eID, $connectionType);
        }
    }


    /**
     * photo /---relatedTo--->/ event
     * @access public
     * @param phID
     * @param eID
     * @return boolean
     */
    public function deleteEventPhoto($phID, $eID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->deleteConnection($this->_pHID, $eID, $connectionType);
        }
    }


    /**
     * event ---locatedAt---> location
     * @access public
     * @param eID (integer)
     * @param locID (integer)
     * @return boolean
     */
    public function addEventLocation($eID, $locID)
    {
        $graphModule = new Graph();
        $this->_eID = $eID;
        $this->_location = $locID;
        if (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } elseif (!filter_var($this->_location, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->addConnection($this->_eID, $this->_location, $connectionType);
        }
    }


    /**
     * event /---locatedAt--->/ location_1
     * event ---locatedAt---> location_2
     * @access public
     * @param eID (integer)
     * @param locID (integer)
     * @param locID2 (integer)
     * @return boolean
     */
    public function changeEventLocation($eID, $locID, $locID2)
    {
        $this->_eID = $eID;
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Previous Location ID is not valid");
        } elseif (!filter_var($locID2, FILTER_VALIDATE_INT)) {
            echo("New Location ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $this->deleteEventLocation($locID, $eID);
            $this->addEventLocation($eID, $locID2);
            $this->setEventLocation($eID, $locID2);
        }
    }


    /**
     * user ---followerOf---> event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function addEventFollower($uID, $eID)
    {
        $graphModule = new Graph();
        $currEventFollowers = $this->getNumberOfEventFollowers($eID);
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->addConnection($this->_uID, $this->_eID, $connectionType);

            $this->setNumberOfEventFollowers($eID, $currEventFollowers + 1);
        }
    }


    /**
     * user /---followerOf--->/ event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function deleteEventFollower($uID, $eID)
    {
        $graphModule = new Graph();
        $currEventFollowers = $this->getNumberOfEventFollowers($eID);
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->deleteConnection($this->_uID, $this->_eID, $connectionType);
            $this->setNumberOfEventFollowers($eID, $currEventFollowers - 1);
        }
    }


    /**
     * user ---organiserOf---> event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function addEventOrganiser($uID, $eID)
    {
        $graphModule = new Graph();
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Organiser ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->addConnection($this->_uID, $this->_eID, $connectionType);
            $this->setEventOrganiserId($eID, $uID);
        }
    }


    /**
     * user1 /---organiserOf--->/ event
     * user2 ---organiserOf---> event
     * @access public
     * @param uID (integer)
     * @param uID2 (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function changeEventOrganiser($uID, $uID2, $eID)
    {
        $this->_eID = $eID;
        if (!filter_var($uID, FILTER_VALIDATE_INT)) {
            echo("Previous Organiser ID is not valid");
        } elseif (!filter_var($uID2, FILTER_VALIDATE_INT)) {
            echo("New Organiser ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $this->deleteEventOrganiser($uID, $eID);
            $this->addEventOrganiser($uID2, $eID);
            $this->setEventOrganiserId($eID, $uID2);
        }
    }


    /**
     * user /---organiserOf--->/ event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function deleteEventOrganiser($uID, $eID)
    {
        $graphModule = new Graph();
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Organiser ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->deleteConnection($this->_uID, $this->_eID, $connectionType);
            $this->setEventOrganiserId($eID, null);
        }
    }


    /**
     * user ---attendeeOf---> event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function addEventAttendee($uID, $eID)
    {
        $graphModule = new Graph();
        $currEventAttendees = $this->getNumberOfEventAttendees($eID);
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Attendee ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'attendeeOf';
            $graphModule->addConnection($this->_uID, $this->_eID, $connectionType);
            $this->setNumberOfEventAttendees($eID, $currEventAttendees + 1);
        }
    }


    /**
     * user /---attendeeOf--->/ event
     * @access public
     * @param uID (integer)
     * @param eID (integer)
     * @return boolean
     */
    public function deleteEventAttendee($uID, $eID)
    {
        $graphModule = new Graph();
        $currEventAttendees = $this->getNumberOfEventAttendees($eID);
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Attendee ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'attendeeOf';
            $graphModule->deleteConnection($this->_uID, $this->_eID, $connectionType);
            $this->setNumberOfEventAttendees($eID, $currEventAttendees - 1);
        }
    }


    /**
     * user ---invitedTo---> event
     * @access public
     * @param uID
     * @param eID
     * @return boolean
     */
    public function addUserEventInvitation($uID, $eID)
    {
        $graphModule = new Graph();
        $currEventInvitees = $this->getNumberOfEventInvitees($eID);
        $this->_uID = $uID;
        $this->_eID = $eID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("User ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'invitedTo';
            $graphModule->addConnection($this->_uID, $this->_eID, $connectionType);
            $this->setNumberOfEventInvitees($eID, $currEventInvitees + 1);
        }
    }


    /**
     * page ---organiserOf---> event
     * @access public
     * @param pID
     * @param eID
     * @return boolean
     */
    public function addEventPage($pID, $eID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_eID = $eID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->addConnection($this->_pID, $this->_eID, $connectionType);
            $this->setEventPageId($eID, $pID);

        }
    }


    /**
     * page_1 /---organiserOf--->/ event
     * page_2 ---organiserOf---> event
     * @access public
     * @param pID
     * @param pID2
     * @param eID
     * @return boolean
     */
    public function changeEventPage($pID, $pID2, $eID)
    {
        $this->_eID = $eID;
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Previous Page ID is not valid");
        } elseif (!filter_var($pID2, FILTER_VALIDATE_INT)) {
            echo("New Page ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $this->deleteEventPage($pID, $eID);
            $this->addEventPage($pID2, $eID);
            $this->setEventPageId($eID, $pID2);
        }
    }


    /**
     * page /---organiserOf--->/ event
     * @access public
     * @param pID
     * @param eID
     * @return boolean
     */
    public function deleteEventPage($pID, $eID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_eID = $eID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->deleteConnection($this->_pID, $this->_eID, $connectionType);
            $this->setEventPageId($eID, null);
        }
    }


    /**
     * group ---organiserOf---> event
     * @access public
     * @param gID
     * @param eID
     * @return boolean
     */
    public function addEventGroup($gID, $eID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        $this->_eID = $eID;
        if (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->addConnection($this->_gID, $this->_eID, $connectionType);
            $this->setEventGroupId($eID, $gID);
        }
    }


    /**
     * group_1 /---organiserOf--->/ event
     * group_2 ---organiserOf---> event
     * @access public
     * @param pID
     * @param pID2
     * @param eID
     * @return boolean
     */
    public function changeEventGroup($gID, $gID2, $eID)
    {
        $this->_eID = $eID;
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Previous Group ID is not valid");
        } elseif (!filter_var($gID2, FILTER_VALIDATE_INT)) {
            echo("New Group ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $this->deleteEventGroup($gID, $eID);
            $this->addEventGroup($gID2, $eID);
            $this->setEventGroupId($eID, $gID2);
        }
    }


    /**
     * group /---organiserOf--->/ event
     * @access public
     * @param gID
     * @param eID
     * @return boolean
     */
    public function deleteEventGroup($gID, $eID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        $this->_eID = $eID;
        if (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } elseif (!filter_var($this->_eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $connectionType = 'organiserOf';
            $graphModule->deleteConnection($this->_gID, $this->_eID, $connectionType);
            $this->setEventGroupId($eID, null);
        }
    }


    /**
     * This method sets the description of an event.
     * @access public
     * @param eID
     * @param description
     * @return boolean
     */
    public function setEventDescription($eID, $description)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_description = $description;
            $update['description'] = $description;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the description of an event.
     * @access public
     * @param eID
     * @return string
     */
    public function getEventDescription($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['description'];
        }
    }


    /**
     * This method sets the date of an event.
     * @access public
     * @param eID
     * @param date
     * @return boolean
     */
    public function setEventDate($eID, $date)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_date = $date;
            $update['date'] = $date;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the date of an event.
     * @access public
     * @param eID
     * @return timestamp
     */
    public function getEventDate($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['date'];
        }
    }


    /**
     * This method sets the event category (public or private).
     * @access public
     * @param eID
     * @param category
     * @return boolean
     */
    public function setEventCategory($eID, $category)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_category = $category;
            $update['category'] = $category;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the event category (public or private).
     * @access public
     * @param eID
     * @return string
     */
    public function getEventCategory($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['category'];
        }
    }


    /**
     * This method sets the value of privacy to 1 if the event is private, 0 otherwise.
     * @access public
     * @param eID
     * @param privacy
     * @return boolean
     */
    public function setEventPrivacy($eID, $privacy)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_privacy = $privacy;
            $update['privacy'] = $privacy;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns 1 if the event if private, 0 otherwise.
     * @access public
     * @param eID
     * @return boolean
     */
    public function getEventPrivacy($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['privacy'];
        }
    }


    /**
     * This method sets the institution of an event.
     * @access public
     * @param eID
     * @param institution
     * @return boolean
     */
    public function setEventInstitution($eID, $institution)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_institution = $institution;
            $update['institution'] = $institution;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the institution that organises an event.
     * @access public
     * @param eID
     * @return string
     */
    public function getEventInstitution($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['institution'];
        }
    }


    /**
     * This method sets the value to 1 if the event charges an attendance fee, 0 otherwise.
     * @access public
     * @param eID
     * @param isPaid
     * @return boolean
     */
    public function setEventPaidState($eID, $isPaid)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_isPaid = $isPaid;
            $update['isPaid'] = $isPaid;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns 1 if the event charges an attendance fee, 0 otherwise.
     * @access public
     * @param eID
     * @return boolean
     */
    public function getEventPaidState($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['isPaid'];
        }
    }


    /**
     * This method sets the payment type of an event.
     * @access public
     * @param eID
     * @param payment
     * @return boolean
     */
    public function setEventPaymentType($eID, $payment)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_paymentType = $payment;
            $update['paymentType'] = $payment;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the payment type an event accepts.
     * @access public
     * @param eID
     * @return integer
     */
    public function getEventPaymentType($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['paymentType'];
        }
    }


    /**
     * This method sets the type of an event.
     * @access public
     * @param eID
     * @param type
     * @return boolean
     */
    public function setEventType($eID, $type)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_eventType = $type;
            $update['eventType'] = $type;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the type of an event.
     * @access public
     * @param eID
     * @return string
     */
    public function getEventType($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['eventType'];
        }
    }


    /**
     * This method sets the timestamp in UTC that an event was created at.
     * @access public
     * @param eID
     * @param timestamp
     * @return boolean
     */
    public function setEventTimestamp($eID, $timestamp)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_timestamp = $timestamp;
            $update['timestamp'] = $timestamp;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the timestamp in UTC indicating when an event was created.
     * @access public
     * @param eID
     * @return timestamp
     */
    public function getEventTimestamp($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['timestamp'];
        }
    }


    /**
     * This method sets the ID of the profile picture of an event.
     * @access public
     * @param eID (integer)
     * @param id (integer)
     * @return boolean
     */
    public function setEventProfPicId($eID, $id)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_profilePicID = $id;
            $update['profilePicID'] = $id;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method get the ID of the profile picture of an event.
     * @access public
     * @param eID (integer)
     * @return integer
     */
    public function getEventProfPicId($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['profilePicID'];
        }
    }


    /**
     * This method sets the location of an event.
     * @access public
     * @param eID (integer)
     * @param location (string)
     * @return boolean
     */
    public function setEventLocation($eID, $location)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_location = $location;
            $update['location'] = $location;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns location of an event.
     * @access public
     * @param eID (integer)
     * @return string
     */
    public function getEventLocation($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['location'];
        }
    }


    /**
     * This method sets the number of followers of an event.
     * @access public
     * @param eID (integer)
     * @param count (integer)
     * @return boolean
     */
    public function setNumberOfEventFollowers($eID, $count)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_followerCount = $count;
            $update['followerCount'] = $count;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the number of followers of an event
     * @access public
     * @param eID (integer)
     * @return integer
     */
    public function getNumberOfEventFollowers($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['followerCount'];
        }
    }


    /**
     * This method sets the ID of the organiser of an event.
     * @access public
     * @param eID (integer)
     * @param organiser (integer)
     * @return boolean
     */
    public function setEventOrganiserId($eID, $organiser)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_organiser = $organiser;
            $update['organiser'] = $organiser;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the ID of the organiser of an event.
     * @access public
     * @param eID (integer)
     * @return integer
     */
    public function getEventOrganiserId($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['organiser'];
        }
    }


    /**
     * This method sets the number of attendees of an event.
     * @access public
     * @param eID (integer)
     * @param attendees (integer)
     * @return boolean
     */
    public function setNumberOfEventAttendees($eID, $attendees)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_attendeeCount = $attendees;
            $update['attendeeCount'] = $attendees;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the number of attendees of an event.
     * @access public
     * @param eID (integer)
     * @return integer
     */
    public function getNumberOfEventAttendees($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['attendeeCount'];
        }
    }


    /**
     * This method sets the number of users who have been invited to an event.
     * @access public
     * @param eID
     * @param invitees
     * @return boolean
     */
    public function setNumberOfEventInvitees($eID, $invitees)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_invitedCount = $invitees;
            $update['invitedCount'] = $invitees;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the number of users who have been invited to an event.
     * @access public
     * @param eID
     * @return integer
     */
    public function getNumberOfEventInvitees($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['invitedCount'];
        }
    }


    /**
     * This method sets the ID of the page of an event.
     * @access public
     * @param eID
     * @param id
     * @return boolean
     */
    public function setEventPageId($eID, $id)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_pID = $id;
            $update['pID'] = $id;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the ID of the page of an event.
     * @access public
     * @param eID
     * @return integer
     */
    public function getEventPageId($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['pID'];
        }
    }


    /**
     * This method sets the ID of the group which organises an event.
     * @access public
     * @param eID
     * @param gID
     * @return boolean
     */
    public function setEventGroupId($eID, $gID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_gID = $gID;
            $update['gID'] = $gID;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method sets the name of an event.
     * @access public
     * @param eID (integer)
     * @param name (string)
     * @return boolean
     */
    public function setEventName($eID, $name)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_name = $name;
            $update['name'] = $name;
            $graphModule->updateNodeById($eID, $update);
        }
    }


    /**
     * This method returns the name of an event.
     * @access public
     * @param eID (integer)
     * @return string
     */
    public function getEventName($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['name'];
        }
    }


    /**
     * This method takes as input the date of an event and returns the forecasted weather for that day.
     * @access public
     * @param aDate
     * @return weather
     */
    public function getWeather($aDate)
    {
        // TODO: getWeather to be implemented
    }


    /**
     * This method gets the user current location and the location of the event and return a route to the event from the current location by using an google maps API.
     * @access public
     * @param aFromLocation
     * @param aToLocation
     */
    public function getDirections($aFromLocation, $aToLocation)
    {
        // TODO: getDirections to be implemented
    }


    /**
     * This method returns the ID of the group which organises an event.
     * @access public
     * @param eID
     * @return integer
     */
    public function getEventGroupId($eID)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($eID);
            $event = $apiCall['data'][0][0]['data'];
            return $event['gID'];
        }
    }


    /**
     * This method sets the ID of the event.
     * @access public
     * @param eID (integer)
     * @param id (integer)
     * @return boolean
     */
    public function setEventId($eID, $id)
    {
        if (!filter_var($eID, FILTER_VALIDATE_INT)) {
            echo("Event ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_eID = $id;
            $update['id'] = $id;
            $graphModule->updateNodeById($eID, $update);
        }
    }


}

?>