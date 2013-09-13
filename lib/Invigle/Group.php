<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Group
{
	private $_name;
	private $_category;
	private $_shortDescription;
	private $_slogan;
	private $_website;
	private $_gID;
	private $_pHID;
	private $_location;
	private $_memberCount;
	private $_institution;
	private $_isPaid;
	private $_paymentType;
	private $_privacy;
	private $_followerCount;
	private $_groupType;
	private $_profilePicID;

	/**
	 * This method takes as input an array with all the information of a group and 
	 * adds this group to the GD as an 'group node'.
	 * @access public
	 * @param aGroupArray
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function addGroup($aGroupArray)
	{
		//Create the new event account in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($aArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$event['query'] = "CREATE (n:Group {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $group);
        
		//return the New Group ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$groupId = end($bit);
		return $groupId;
	}

	/**
	 * This method takes as input the ID of a group and deletes the node that represent this group from the GD.
	 * @access public
	 * @param aGID
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function deleteGroup($aGID)
	{
		// Not yet implemented
	}

	/**
	 * This method edits some of the properties of a group in the GD by updating the current node in the GD with information provided by the groupArray which is the input to the editGroup method
	 * @access public
	 * @param aGroupArray
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function editGroup($aGroupArray)
	{
		// Not yet implemented
	}

	/**
	 * This method returns the name of the group.
	 * @access public
	 * @return string
	 */
	public function getGroupName()
	{
		return $this->_name;
	}

	/**
	 * This method sets the name of the group.
	 * @access public
	 * @param name (string)
	 * @return boolean
	 */
	public function setGroupName($name)
	{
		$this->_name = $name;
	}

	/**
	 * This method returns the group category.
	 * @access public
	 * @return string
	 */
	public function getGroupCategory()
	{
		return $this->_category;
	}

	/**
	 * This method sets the group category.
	 * @access public
	 * @param category (string))
	 * @return boolean
	 */
	public function setGroupCategory($category)
	{
		$this->_category = $category;
	}

	/**
	 * This method returns the group description.
	 * @access public
	 * @return string
	 */
	public function getGroupDescription()
	{
		return $this->_description;
	}

	/**
	 * This method sets the group description.
	 * @access public
	 * @param description (string)
	 * @return boolean
	 */
	public function setGroupDescription($description)
	{
		$this->_description = $description;
	}

	/**
	 * This method returns the group's slogan.
	 * @access public
	 * @return string
	 */
	public function getGroupSlogan()
	{
		return $this->_slogan;
	}

	/**
	 * This method sets the group's slogan.
	 * @access public
	 * @param slogan (string)
	 * @return boolean
	 */
	public function setGroupSlogan($slogan)
	{
		$this->_slogan = $slogan;
	}

	/**
	 * This method returns the group's website url.
	 * @access public
	 * @return url
	 */
	public function getGroupWebsite()
	{
		return $this->_website;
	}

	/**
	 * This method sets the group's website url.
	 * @access public
	 * @param website (url)
	 * @return boolean
	 */
	public function setGroupWebsite($website)
	{
		$this->_website = $website;
	}

	/**
	 * This method returns the group ID.
	 * @access public
	 * @return integer
	 */
	public function getGroupId()
	{
		return $this->_gID;
	}

	/**
	 * This method sets the group ID.
	 * @param id (integer)
	 * @return boolean
	 */
	public function setGroupId($id)
	{
		$this->_gID = $id;
	}

	/**
	 * This method returns location of the group.
	 * @access public
	 * @return string
	 */
	public function getGroupLocation()
	{
		return $this->_location;
	}

	/**
	 * This method sets the location of the event.
	 * @access public
	 * @param location (string)
	 * @return boolean
	 */
	public function setGroupLocation($location)
	{
		$this->_location = $location;
	}

	/**
	 * This method returns the number of group members. 
	 * @access public
	 * @return integer
	 */
	public function getNumberOfGroupMembers()
	{
		return $this->_memberCount;
	}

	/**
	 * This method sets the number of group members.
	 * @access public
	 * @param memberCount(integer)
	 * @return boolean
	 */
	public function setNumberOfGroupMembers($memberCount)
	{
		$this->_memberCount = $memberCount;
	}

	/**
	 * This method returns the institution the group is associated with.
	 * @access public
	 * @return string
	 */
	public function getGroupInstitution()
	{
		return $this->_institution;
	}

	/**
	 * This method sets the institution the group is associated with.
	 * @param institution (string)
	 * @return boolean
	 */
	public function setGroupInstitution($institution)
	{
		$this->_institution = $institution;
	}

	/**
	 * This method returns 1 if the group charges a memmbership fee, 0 otherwise.
	 * @access public
	 * @return boolean
	 */
	public function getGroupPaidState()
	{
		return $this->_isPaid;
	}

	/**
	 * This method sets the _isPaid value to 1 if the group charges a memmbership fee, 0 otherwise.
	 * @access public
	 * @param paid (boolean)
	 * @return boolean
	 */
	public function setGroupPaidState($paid)
	{
		$this->_isPaid = $paid;
	}

	/**
	 * This method returns the payment type the group accepts memberships by.
	 * @access public
	 * @return integer
	 */
	public function getGroupPaymentType()
	{
		return $this->_paymentType;
	}

	/**
	 * This method sets the payment type the group accepts memberships by.
	 * @access public
	 * @param payment (integer)
	 * @return boolean
	 */
	public function setGroupPaymentType($payment)
	{
		$this->_paymentType = $payment;
	}

	/**
	 * This method returns 1 if the group is private, 0 otherwise.
	 * @access public
	 * @return boolean 
	 */
	public function getGroupPrivacy()
	{
		return $this->_privacy;
	}

	/**
	 * This method sets the value _privacy to 1 if the group is private, 0 otherwise.
	 * @access public
	 * @param privacy (boolean)
	 * @return boolean
	 */
	public function setGroupPrivacy($privacy)
	{
		$this->_privacy = $privacy;
	}

	/**
	 * This method returns the number of followers of the group.
	 * @return integer
	 */
	public function getNumberOfGroupFollowers()
	{
		return $this->$_followerCount;
	}

	/**
	 * This method sets the number of followers of the group.
	 * @access public
	 * @param count (integer)
	 * @return boolean
	 */
	public function setNumberOfGroupFollowers($count)
	{
		$this->_followerCount = $count;
	}

	/**
	 * This method returns the ID of the group profile picture.
	 * @access public
	 * @return integer
	 */
	public function getGroupProfPicId()
	{
		return $this->_profilePicID;
	}

	/**
	 * This method sets the ID of the group profile picture.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setGroupProfPicId($id)
	{
		$this->_profilePicID = $id;
	}
    
    /**
	 * This method returns the ID of the photo the comment is on.
	 * @access public
	 * @return integer
	 */
	public function getCommentPhotoId()
	{
		return $this->_pHID;
	}

	/**
	 * This method sets the ID of the photo the comment is on.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setCommentPhotoId($id)
	{
		$this->$_pHID = $id;
	}
    
    /**
	 * This method returns the group type.
	 * @access public
	 * @return string
	 */
	public function getGroupType()
	{
		return $this->_groupType;
	}

	/**
	 * This method sets the group type.
	 * @access public
	 * @param type (string))
	 * @return boolean
	 */
	public function setGroupType($type)
	{
		$this->_groupType = $type;
	}
}
?>