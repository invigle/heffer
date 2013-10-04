<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access public
 */
class Group
{
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
     * the GD with information provided by the gArray.
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