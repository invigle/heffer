<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access public
 */
class Group
{

	private $_name;
	private $_category;
	private $_shortDescription;
	private $_slogan;
	private $_website;
	private $_location;
	private $_memberCount;
	private $_institution;
	private $_isPaid;
	private $_paymentType;
	private $_privacy;
	private $_followerCount;
	private $_groupType;
	private $_profilePicID;
	private $_nodeType;
	private $_eID;
	private $_gID;
	private $_pHID;

	/* The Class Constructor*/
	public function __construct()
	{
		$this->_name = null;
		$this->_category = null;
		$this->_shortDescription = null;
		$this->_slogan = null;
		$this->_website = null;
		$this->_location = null;
		$this->_memberCount = null;
		$this->_institution = null;
		$this->_isPaid = null;
		$this->_paymentType = null;
		$this->_privacy = null;
		$this->_followerCount = null;
		$this->_groupType = null;
		$this->_profilePicID = null;
		$this->_nodeType = 'Group';
		$this->_eID = null;
		$this->_gID = null;
		$this->_pHID = null;
	}

	/**
	 * Find the ID of a category using cypher indexBy and indexValue
	 */
	public function getCategoryId(array $params)
	{
		$path = "cypher";
		$postfields['query'] = "MATCH n:Category WHERE n.$params[indexBy]='$params[indexValue]' RETURN n;";
		$api = $this->neo4japi('cypher', 'JSONPOST', $postfields);
		if (isset($api['data'][0]))
		{
			$categoryID = explode("/", $api['data']['0']['0']['self']);
			return end($categoryID);
		}
		$this->_category = $categoryID;
	}

	/**
	 * This method takes as input an array with all the information of a group and 
	 * adds this group to the GD as an 'group node'.
	 * @access public
	 * @param gArray
	 * @return integer
	 */
	public function addGroup($gArray)
	{
		$graphModule = new Graph();
        
        $groupData = array(
            'name'=>$gArray['name'],
            'categories'=>$gArray['categories'],
            'description'=>$gArray['description'],
            'slogan'=>$gArray['slogan'],
            'website'=>$gArray['website'],
            'location'=>$gArray['location'],
            'institution'=>$gArray['institution'],
            'privacy'=>$gArray['privacy'],
            'type'=>$gArray['type'],
            'ProfilePicID'=>"",
            'followerCount'=>"0",
            'memberCount'=>"0",
            'isPaid'=>"",
            'paymentType'=>"",
            'timestamp'=>time(),
        );
        
        if(isset($gArray['isPaid'])){
            $newEventArray['isPaid'] = $gArray['isPaid'];
            $newEventArray['paymentType'] = $gArray['paymentType'];
        }
        
        $groupId = $graphModule->createNode('Group', $groupData);
        
        //Update Users Timeline if Selected.
        $creatorId = $_SESSION['uid'];
        if(isset($gArray['timeline'])){
            //Add to users Timeline
            $createActionProperties = array(
                'actionType'=>'newGroup',
                'timestamp'=>time(),
                'uid'=>$groupId,
            );
            
            $newGroupActionId = $graphModule->createNode('Action', $createActionProperties);
            
            $userModule = new User();
            $userModule->updateUserTimeline($_SESSION['uid'], $newGroupActionId);
        }
        
        //Add the adminOf Connection.
        $graphModule->addConnection($creatorId, $groupId, 'adminOf');
        
    $this->_gID = $groupId;
    return $groupId;
	}

	/** Function to delete a group node given an ID.
	 * @access private
	 * @param gID
	 */
	public function deleteGroup($gID)
	{
		$graph = new Graph();
		$succ = $graph->deleteNodeByID($gID);
		if (!$succ)
		{
			throw new Exception("Group $gID could not be deleted.");
		}
	}

	/**
	 * This method edits some of the properties of a group in the GD by updating the current node in 
	 * the GD with information provided by the gArray which is the input to the editGroup method
	 * @access public
	 * @param gArray
	 * @return boolean
	 */
	public function editGroup($gArray)
	{
		$graph = new Graph();
		$succ = $graph->editNodeProperties($gArray);
		return $succ;
	}

	/**
	 * This method takes as inputs a group ID the ID of the category and then it adds a HAS edge to neo4j.
	 * @access public
	 * @param gID, catID
	 */
	public function addGroupCategory($gID, $catID)
	{
		$graph = new Graph();
		$connectionType = 'HAS';
		$succ = $graph->addConnection($gID, $catID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Category $catID could not be added to group $gID.");
		}
		$this->_category = $gID;
	}

	/**
	 *  This method takes as inputs a group ID the ID of the category and then it adds a HAS edge from neo4j.
	 * @access public
	 * @param gID, catID
	 */
	public function deleteGroupCategory($gID, $catID)
	{
		$graph = new Graph();
		$connectionType = 'HAS';
		$succ = $graph->deleteConnection($gID, $catID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Category $catID could not be deleted from group $gID.");
		}
		$this->_category = null;
	}

	/**
	 * This method takes as inputs a group ID and a location ID and adds a LOCATED_AT edge to neo4j.
	 * @access public
	 * @param gID, locID
	 */
	public function addGroupLocation($gID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->addConnection($gID, $locID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Location $locID could not be added to group $gID.");
		}
		$this->_location = $locID;
	}

	/**
	 * This method takes as inputs a page ID and a location ID and deletes a LOCATED_AT edge from neo4j.
	 * @access public
	 */
	public function deleteGroupLocation($gID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->deleteConnection($gID, $locID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Location $locID could not be deleted from group $gID.");
		}
		$this->_location = null;
	}

	/**
	 * This method takes as inputs a group ID and a event ID and adds an ORGANISER_OF edge to neo4j.
	 * @access public
	 * @param gID, eID
	 */
	public function addGroupEvent($gID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->addConnection($gID, $eID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Event $eID could not be added to group $gID.");
		}
		$this->_eID = $eID;
	}

	/**
	 * This method takes as inputs a group ID and a event ID and deletes a ORGANISER_OF edge from neo4j.
	 * @access public
	 * @param gID, eID
	 */
	public function deleteGroupEvent($gID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->deleteConnection($gID, $eID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Event $eID could not be deleted from group $gID.");
		}
		$this->_eID = null;
	}

	public function addGroupPhoto($phID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'RELATED_TO';
		$succ = $graph->addConnection($phID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Photo $phID could not be related to group $gID.");
		}
		$this->_pHID = $phID;
	}

	public function deleteGroupPhoto($phID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'RELATED_TO';
		$succ = $graph->deleteConnection($phID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("Photo $phID related to group $gID couldn't be deleted.");
		}
		$this->_pHID = null;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a FOLLOWER_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 */
	public function addGroupFollower($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be added to the followers of $gID.");
		}
		$this->_followerCount += 1;
	}

	public function deleteGroupFollower($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->deleteConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be deleted from the followers of $gID.");
		}
		$this->_followerCount -= 1;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a ADMIN_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 */
	public function addGroupAdmin($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be added as admin of group $gID.");
		}
	}

	public function deleteGroupAdmin($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->deeleteConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be removed as admin of group $gID.");
		}
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a MEMBER_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 */
	public function addGroupMember($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'MEMBER_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be added as member of group $gID.");
		}
		$this->_memberCount += 1;
	}

	public function deleteGroupMember($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'MEMBER_OF';
		$succ = $graph->deleteConnection($uID, $gID, $connectionType);
		if (!$succ)
		{
			throw new Exception("User $uID could not be deleted from the members of group $gID.");
		}
		$this->_memberCount -= 1;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a INVITED_TO edge to neo4j.
	 * @access public
	 * @param uID, gID
	 * @return boolean
	 */
	public function addUserGroupInvitation($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'INVITED_TO';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		return $succ;
	}

	/**********************************************************/
	/** SETS and GETS
	 * /**********************************************************/

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
    private $_name;
    private $_shortDescription;
    private $_category;
    private $_slogan;
    private $_website;
    private $_location;
    private $_memberCount;
    private $_institution;
    private $_isPaid;
    private $_paymentType;
    private $_privacy;
    private $_followerCount;
    private $_type;
    private $_profilePicID;
    private $_nodeType;
    private $_eID;
    private $_gID;
    private $_pHID;
    private $_uID;
    private $_admin;
    private $_inviteeCount;

    /* The Class Constructor*/

    public function __construct()
    {
        $this->_name = null;
        $this->_category = null;
        $this->_shortDescription = null;
        $this->_slogan = null;
        $this->_website = null;
        $this->_location = null;
        $this->_institution = null;
        $this->_isPaid = null;
        $this->_paymentType = null;
        $this->_privacy = null;
        $this->_followerCount = null;
        $this->_memberCount = null;
        $this->_memberCount = null;
        $this->_type = null;
        $this->_profilePicID = null;
        $this->_nodeType = 'Group';
        $this->_eID = null;
        $this->_pHID = null;
        $this->_uID = null;
        $this->_admin = null;
        $this->_inviteeCount = null;
    }

    /* This method takes as input an array with all the information of a group and
     * adds this group to the GD as a group node.
     * @access public
     * @param gArray
     */
    public function addGroup($gArray)
    {
        $graphModule = new Graph();

        $newGroupArray = array(
            'name' => $gArray['name'],
            'shortDescription' => $gArray['shortDescription'],
            'category' => $gArray['category'],
            'slogan' => $gArray['slogan'],
            'website' => $gArray['website'],
            'location' => $gArray['location'],
            'institution' => $gArray['institution'],
            'privacy' => $gArray['privacy'],
            'followerCount' => '0',
            'memberCount' => $gArray['memberCount'],
            'type' => $gArray['type'],
            'profilePicID' => '',
            'eID' => '',
            'phID' => '',
            'admin' => $gArray['admin'],
            'inviteeCount' => $gArray['inviteeCount'],
        );

        // If the group is paid, the array of the new group is populated
        // with the field isPaid and the payment type.
        if (isset($gArray['isPaid'])) {
            $newGroupArray['isPaid'] = $gArray['isPaid'];
            $newGroupArray['paymentType'] = $gArray['paymentType'];
        }

        // The array of the new group gets the admin of the group and her/his ID.
        if ($gArray['adminGroupAs'] === "user") {
            $newGroupArray['ownerType'] = "user";
            $newGroupArray['OwnerID'] = $_SESSION['uid'];
        }

        // Creating a group node.
        $groupId = $graphModule->createNode('Group', $newGroupArray);

        if ($gArray['adminGroupAs'] === "user") {
            // Get the ID of the admin of the group.
            $adminId = $_SESSION['uid'];
            // Set the properties of the createActionProperties array.
            if (isset($eArray['timeline'])) {
                $createActionProperties = array(
                    'actionType' => 'newGroup',
                    'timestamp' => time(),
                    'uid' => $groupId,
                );

                // Create a action node.
                $newGroupActionId = $graphModule->createNode('Action', $createActionProperties);

                // Create a new user (the admin of the group) in order to update his/her timeline.
                $userModule = new User();

                // Update the user's timeline by connecting the ID of the admin with the ID of the
                // new action which shows the creation of a new group.
                $userModule->updateUserTimeline($_SESSION['uid'], $newGroupActionId);
            }
            $this->_gID = $groupId;
            // Add a connection from the admin node to the group node.
            $graphModule->addConnection($adminId, $this->_gID, 'adminOf');
        }
    }

    /* This method deletes a group node given an ID.
     * @access public
     * @param gID
     */
    public function deleteGroup($gID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        if (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule->deleteNodeByID($this->_gID);
            $this->setGroupId($gID, null);
        }
    }

    /**
     * This method edits some of the properties of a group in the GD by updating the current node in
     * the GD with information provided by the gArray which is the input to the editGroup method.
     * @access public
     * @param gArray
     * @return boolean
     */
    public function editGroup($gArray)
    {
        $graphModule = new Graph();

        $newGroupArray = array(
            'name' => $gArray['name'],
            'shortDescription' => $gArray['shortDescription'],
            'category' => $gArray['category'],
            'slogan' => $gArray['slogan'],
            'website' => $gArray['website'],
            'location' => $gArray['location'],
            'institution' => $gArray['institution'],
            'privacy' => $gArray['privacy'],
            'followerCount' => $gArray['followerCount'],
            'memberCount' => $gArray['memberCount'],
            'type' => $gArray['type'],
            'profilePicID' => $gArray['profilePicID'],
            'eID' => $gArray['eID'],
            'phID' => $gArray['phID'],
            'admin' => $gArray['admin'],
            'inviteeCount' => $gArray['inviteeCount'],
        );

        $graphModule->editNodeProperties($newGroupArray);
    }

    /* This method sets the ID of a group.
   * @access public
   * @param gID, id
   * @return boolean
   */
    public function setGroupId($gID, $id)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_gID = $id;
            $update['id'] = $id;
            $graphModule->updateNodeById($gID, $update);
        }
    }

    /**
     * This method takes as inputs a user ID and a group ID and adds a adminOf edge to neo4j.
     * @access public
     * @param uID, gID
     * @return boolean
     */
    public function addGroupAdmin($uID, $gID)
    {
        $graphModule = new Graph();
        $this->_admin = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_admin, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->addConnection($this->_admin, $this->_gID, $connectionType);
            $this->setGroupAdminId($gID, $this->_admin);
        }
    }


    public function changeGroupAdmin($uID, $uID2, $gID)
    {
        $this->_gID = $gID;
        if (!filter_var($uID, FILTER_VALIDATE_INT)) {
            echo("Previous Admin ID is not valid");
        } elseif (!filter_var($uID2, FILTER_VALIDATE_INT)) {
            echo("New Admin ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $this->deleteGroupAdmin($uID, $gID);
            $this->addGroupAdmin($uID2, $gID);
            $this->setGroupAdminId($gID, $uID2);
        }
    }

    public function deleteGroupAdmin($uID, $gID)
    {
        $graphModule = new Graph();
        $this->_admin = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_admin, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->deleteConnection($this->_admin, $this->_gID, $connectionType);
            $this->setGroupAdminId($gID, null);
        }
    }

    /**
     * This method returns the ID of the admin of the group.
     * @access public
     * @return integer
     */
    public function getGroupAdminId($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['admin'];
        }
    }

    /**
     * This method sets the ID of the admin of the group.
     * @access public
     * @param id (integer)
     * @return boolean
     */
    public function setGroupAdminId($gID, $admin)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_admin = $admin;
            $update['admin'] = $admin;
            $graphModule->updateNodeById($gID, $update);
        }
    }

    /**
     * This method takes as inputs a group ID the ID of the category and then
     * it adds a Has edge to neo4j.
     * @access public
     * @param gID, catID
     */
    public function addGroupCategory($catID, $gID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        $this->_category = $catID;
        if (!filter_var($this->_category, FILTER_VALIDATE_INT)) {
            echo("Category ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'has';
            $graphModule->addConnection($this->_category, $this->_gID, $connectionType);
            $this->setGroupCategoryId($gID, $catID);
        }
    }

    /**
     *  This method takes as inputs a group ID the ID of the category and then it adds a HAS edge from neo4j.
     * @access public
     * @param gID, catID
     */
    public function deleteGroupCategory($catID, $gID)
    {
        $graphModule = new Graph();
        $this->_category = $catID;
        $this->_gID = $gID;
        if (!filter_var($this->_category, FILTER_VALIDATE_INT)) {
            echo("Category ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'has';
            $graphModule->deleteConnection($this->_category, $this->_gID, $connectionType);
            $this->setGroupCategoryId($gID, null);
        }
    }


    /**
     * This method returns the ID of the category of the group.
     * @access public
     * @return integer
     */
    public function getGroupCategoryId($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['category'];
        }
    }

    /**
     * This method sets the category of the group.
     * @access public
     * @param location (string)
     * @return boolean
     */
    public function setGroupCategoryId($gID, $category)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_category = $category;
            $update['category'] = $category;
            $graphModule->updateNodeById($gID, $update);
        }
    }

    /**
     * This method takes as inputs a photo ID, the ID of a group and adds a relatedTo edge to neo4j.
     * @access public
     * @param phID, gID
     */
    public function addGroupPhoto($phID, $gID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->addConnection($this->_pHID, $gID, $connectionType);
            $this->setGroupPhotoId($gID, $phID);
        }
    }

    /**
     * This method takes as inputs a photo ID, the ID of a group and deletes a
     * relatedTo edge from neo4j.
     * @access public
     * @param phID, gID
     */
    public function deleteGroupPhoto($phID, $gID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->deleteConnection($this->_pHID, $gID, $connectionType);
            $this->setGroupPhotoId($gID, null);
        }
    }

    /**
     * This method returns the ID of a photo of the group.
     * @access public
     * @return integer
     */
    public function getGroupPhotoId($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['phID'];
        }
    }

    /**
     * This method sets the ID of the photo of a group.
     * @access public
     * @param id (integer)
     * @return boolean
     */
    public function setGroupPhotoId($gID, $photo)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_pHID = $photo;
            $update['phID'] = $this->_pHID;
            $graphModule->updateNodeById($gID, $update);
        }
    }


    /**
     * This method takes as inputs a group ID and a location ID and adds a locatedAt edge to neo4j.
     * @access public
     * @param gID, locID
     */
    public function addGroupLocation($gID, $locID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        $this->_location = $locID;
        if (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } elseif (!filter_var($this->_location, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->addConnection($this->_gID, $this->_location, $connectionType);
            $this->setGroupLocationId($gID, $locID);
        }
    }


    public function changeGroupLocation($locID, $locID2, $gID)
    {
        $this->_gID = $gID;
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Previous Location ID is not valid");
        } elseif (!filter_var($locID2, FILTER_VALIDATE_INT)) {
            echo("New Location ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $this->deleteGroupLocation($locID, $gID);
            $this->addGroupLocation($locID2, $gID);
            $this->setGroupLocationId($gID, $locID2);
        }
    }

    /**
     * This method takes as inputs a group ID and a location ID and deletes a locatedAt edge from neo4j.
     * @access public
     * @param gID, locID
     */
    public function deleteGroupLocation($gID, $locID)
    {
        $graphModule = new Graph();
        $this->_gID = $gID;
        $this->_location = $locID;
        if (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } elseif (!filter_var($this->_location, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->deleteConnection($this->_gID, $this->_location, $connectionType);
            $this->setGroupLocationId($gID, null);
        }
    }

    /**
     * This method returns the ID of the location of the group.
     * @access public
     * @return integer
     */
    public function getGroupLocationId($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['location'];
        }
    }


    /**
     * This method sets the location of the group.
     * @access public
     * @param location (string)
     * @return boolean
     */
    public function setGroupLocationId($gID, $location)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_location = $location;
            $update['location'] = $location;
            $graphModule->updateNodeById($gID, $update);
        }
    }


    /**
     * This method takes as inputs a user ID and a group ID and adds a followerOf edge to neo4j.
     * @access public
     * @param uID, gID
     * @return boolean
     */
    public function addGroupFollower($uID, $gID)
    {
        $graphModule = new Graph();
        $currGroupFollowers = $this->getNumberOfGroupFollowers($gID);
        $this->_uID = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->addConnection($this->_uID, $this->_gID, $connectionType);
            $this->setNumberOfGroupFollowers($gID, $currGroupFollowers + 1);
        }
    }

    public function deleteGroupFollower($uID, $gID)
    {
        $graphModule = new Graph();
        $currGroupFollowers = $this->getNumberOfGroupFollowers($gID);
        $this->_uID = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->deleteConnection($this->_uID, $this->_gID, $connectionType);
            $this->setNumberOfGroupFollowers($gID, $currGroupFollowers - 1);
        }
    }

    /**
     * This method returns the number of followers of the group.
     * @access public
     * @return integer
     */
    public function getNumberOfGroupFollowers($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['followerCount'];
        }
    }

    /**
     * This method sets the number of followers of the group.
     * @access public
     * @param followers (integer)
     * @return boolean
     */
    public function setNumberOfGroupFollowers($gID, $followers)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_followerCount = $followers;
            $update['followerCount'] = $followers;
            $graphModule->updateNodeById($gID, $update);
        }
    }


    /**
     * This method takes as inputs a user ID and a group ID and adds a memberOf edge to neo4j.
     * @access public
     * @param uID, gID
     * @return boolean
     */
    public function addGroupMember($uID, $gID)
    {
        $graphModule = new Graph();
        $currGroupMembers = $this->getNumberOfGroupMembers($gID);
        $this->_uID = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Member ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'memberOf';
            $graphModule->addConnection($this->_uID, $this->_gID, $connectionType);
            $this->setNumberOfGroupMembers($gID, $currGroupMembers + 1);
        }
    }

    public function deleteGroupMember($uID, $gID)
    {
        $graphModule = new Graph();
        $currGroupMembers = $this->getNumberOfGroupMembers($gID);
        $this->_uID = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Member ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'memberOf';
            $graphModule->deleteConnection($this->_uID, $this->_gID, $connectionType);
            $this->setNumberOfGroupMembers($gID, $currGroupMembers - 1);
        }
    }

    /**
     * This method returns the number of members of the group.
     * @access public
     * @return integer
     */
    public function getNumberOfGroupMembers($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['memberCount'];
        }
    }


    /*
    * In the following we have all method that set and get
    * properties of the class.
    */

    /**
     * This method sets the number of members of the group.
     * @access public
     * @param followers (integer)
     * @return boolean
     */
    public function setNumberOfGroupMembers($gID, $members)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_memberCount = $members;
            $update['memberCount'] = $members;
            $graphModule->updateNodeById($gID, $update);
        }
    }


    /**
     * This method takes as inputs a user ID and a group ID and adds an invitedTo edge to neo4j.
     * @access public
     * @param uID, gID
     * @return boolean
     */
    public function addUserGroupInvitation($uID, $gID)
    {
        $graphModule = new Graph();
        $currGroupInvitees = $this->getNumberOfGroupInvitees($gID);
        $this->_uID = $uID;
        $this->_gID = $gID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("User ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $connectionType = 'invitedTo';
            $graphModule->addConnection($this->_uID, $this->_gID, $connectionType);
            $this->setNumberOfGroupInvitees($gID, $currGroupInvitees + 1);
        }
    }

    /**
     * This method returns the number of invitees of the group.
     * @access public
     * @return integer
     */
    public function getNumberOfGroupInvitees($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['inviteeCount'];
        }
    }

    /**
     * This method sets the number of invitees of the group.
     * @access public
     * @param followers (integer)
     * @return boolean
     */
    public function setNumberOfGroupInvitees($gID, $invitees)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_inviteeCount = $invitees;
            $update['inviteeCount'] = $invitees;
            $graphModule->updateNodeById($gID, $update);
        }
    }
}

?>
