<?php

namespace Invigle;
use Invigle\Graph;

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
        $this->_eID = null;
        $this->_pHID = null;
        $this->_uID = null;
        $this->_admin = null;
        $this->_inviteeCount = null;
    }


    /* This method adds a group node to the GD.
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
            'paymentType' => $gArray['paymentType'],
            'privacy' => $gArray['privacy'],
            'followerCount' => '0',
            'memberCount' => $gArray['memberCount'],
            'type' => $gArray['type'],
            'profilePicID' => '',
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


    /* This method deletes a group node.
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
            $this->setGroup($gID, null);
        }
    }


    /**
     * This method edits some of the properties of a group in the
     * GD by updating the current node with information provided by the gArray.
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


    /**
     * user_1 /---adminOf--->/ group
     * user_2 ---adminOf---> group
     * @access public
     * @param uID (integer)
     * @param uID2 (integer)
     * @param gID (integer)
     * @return boolean
     */
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
            $this->setGroupAdmin($gID, $uID2);
        }
    }


    /**
     * user /---adminOf--->/ group
     * @access public
     * @param uID (integer)
     * @param gID (integer)
     * @return boolean
     */
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
            $this->setGroupAdmin($gID, null);
        }
    }


    /**
     * user ---adminOf---> group
     * @access public
     * @param uID (integer)
     * @param gID (integer)
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
            $this->setGroupAdmin($gID, $this->_admin);
        }
    }


    /**
     * group ---has---> category
     * @param gID (integer)
     * @param catID (integer)
     * @return boolean
     */
    public function addGroupCategory($gID, $catID)
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
            $graphModule->addConnection($this->_gID, $this->_category, $connectionType);
            $this->setGroupCategory($gID, $catID);
        }
    }


    /**
     * group /---has--->/ category
     * @access public
     * @param gID (integer)
     * @param catID (integer)
     * @return boolean
     */
    public function deleteGroupCategory($gID, $catID)
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
            $graphModule->deleteConnection($this->_gID, $this->_category, $connectionType);
            $this->setGroupCategory($gID, null);
        }
    }


    /**
     * photo ---relatedTo---> group
     * @access public
     * @param phID (integer)
     * @param gID (integer)
     * @return boolean
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
            $this->setGroupPhoto($gID, $phID);
        }
    }


    /**
     * photo /---relatedTo--->/ group  (photo = profile picture)
     * @access public
     * @param phID (integer)
     * @param gID (integer)
     * @return boolean
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
            $this->setGroupPhoto($gID, null);
        }
    }


    /**
     * group /---locatedAt--->/ location_1
     * group ---locatedAt---> location_2
     * @access public
     * @param gID (integer)
     * @param locID (integer)
     * @param locID2 (integer)
     * @return boolean
     */
    public function changeGroupLocation($gID, $locID, $locID2)
    {
        $this->_gID = $gID;
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Previous Location ID is not valid");
        } elseif (!filter_var($locID2, FILTER_VALIDATE_INT)) {
            echo("New Location ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $this->deleteGroupLocation($gID, $locID);
            $this->addGroupLocation($gID, $locID2);
            $this->setGroupLocation($gID, $locID2);
        }
    }


    /**
     * group /---locatedAt--->/ location
     * @access public
     * @param gID (integer)
     * @param location (integer)
     * @return boolean
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
            $this->setGroupLocation($gID, null);
        }
    }


    /**
     * group ---locatedAt---> location
     * @access public
     * @param gID (integer)
     * @param location (integer)
     * @return boolean
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
            $this->setGroupLocation($gID, $locID);
        }
    }


    /**
     * user ---followerOf---> group
     * @access public
     * @param uID (integer)
     * @param gID (integer)
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


    /**
     * user /---followerOf--->/ group
     * @access public
     * @param uID (integer)
     * @param gID (integer)
     * @return boolean
     */
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
     * user ---memberOf---> group
     * @access public
     * @param $uID (integer)
     * @param $gID (integer)
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


    /**
     * user /---memberOf--->/ group
     * @access public
     * @param $uID (integer)
     * @param $gID (integer)
     * @return boolean
     */
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
     * user ---invitedTo---> group
     * @access public
     * @param uID (integer)
     * @param gID (integer)
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
     * This method sets the ID of the admin of a group.
     * @access public
     * @param gID (integer)
     * @param admin (integer)
     * @return boolean
     */
    public function setGroupAdmin($gID, $admin)
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
     * This method returns the ID of the admin of a group.
     * @access public
     * @param gID (integer)
     * @return integer
     */
    public function getGroupAdmin($gID)
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
     * This method sets the ID of the category of the group.
     * @access public
     * @param gID (integer)
     * @param
     * @return boolean
     */
    public function setGroupCategory($gID, $category)
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
     * This method returns the category node of a group.
     * @access public
     * @param gID (integer)
     * @return integer
     */
    public function getGroupCategory($gID)
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
     * This method sets the ID of the profile picture (photo) of a group.
     * @access public
     * @param gID (integer)
     * @param photo (integer)
     * @return boolean
     */
    public function setGroupPhoto($gID, $photo)
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
     * This method returns the ID of the profile picture (photo) of the group.
     * @access public
     * @param gID (integer)
     * @return integer
     */
    public function getGroupPhoto($gID)
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
     * This method sets the location ID of a group.
     * @access public
     * @param gID (integer)
     * @param location (integer)
     * @return boolean
     */
    public function setGroupLocation($gID, $location)
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
     * This method returns the ID of the location of the group.
     * @access public
     * @param gID (integer)
     * @return integer
     */
    public function getGroupLocation($gID)
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
     * This method sets the number of followers of the group.
     * @access public
     * @param $gID (integer)
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
     * This method returns the number of followers of the group.
     * @access public
     * @param $gID (integer)
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
     * This method sets the number of members of the group.
     * @access public
     * @param gID (integer)
     * @param members (integer)
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
     * This method returns the number of members of the group.
     * @access public
     * @param gID (integer)
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


    /**
     * This method sets the number of invitees of the group.
     * @access public
     * @param gID (integer)
     * @param invitees (integer)
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


    /**
     * This method returns the number of invitees of the group.
     * @access public
     * @param gID (integer)
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
     * This method sets the name of a group.
     * @access public
     * @param gID (integer)
     * @param name (string)
     * @return boolean
     */
    public function setGroupName($gID, $name)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_name = $name;
            $update['name'] = $name;
            $graphModule->updateNodeById($gID, $update);
        }
    }


    /**
     * This method returns the name of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupName($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['name'];
        }
    }


    /**
     * This method sets the short description of a group.
     * @access public
     * @param gID (integer)
     * @param shortDescription (string)
     * @return boolean
     */
    public function setGroupDescription($gID, $shortDescription)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $shortDescription;
            $update['shortDescription'] = $shortDescription;
            $graphModule->updateNodeById($gID, $shortDescription);
        }
    }


    /**
     * This method returns the short description of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupDescription($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['shortDescription'];
        }
    }


    /**
     * This method sets the slogan of a group.
     * @access public
     * @param gID (integer)
     * @param slogan (string)
     * @return boolean
     */
    public function setGroupSlogan($gID, $slogan)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $slogan;
            $update['slogan'] = $slogan;
            $graphModule->updateNodeById($gID, $slogan);
        }
    }


    /**
     * This method returns the slogan of a group.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getGroupSlogan($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['slogan'];
        }
    }


    /**
     * This method sets the website of a group.
     * @access public
     * @param gID (integer)
     * @param website (string)
     * @return boolean
     */
    public function setGroupWebsite($gID, $website)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_website = $website;
            $update['website'] = $website;
            $graphModule->updateNodeById($gID, $website);
        }
    }


    /**
     * This method returns the website of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupWebsite($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['website'];
        }
    }


    /**
     * This method sets the institution of a group.
     * @access public
     * @param gID (integer)
     * @param institution (string)
     * @return boolean
     */
    public function setGroupInstitution($gID, $institution)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_institution = $institution;
            $update['institution'] = $institution;
            $graphModule->updateNodeById($gID, $institution);
        }
    }


    /**
     * This method returns the institution of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupInstitution($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['institution'];
        }
    }


    /**
     * This method sets the payment type of a group.
     * @access public
     * @param gID (integer)
     * @param payment (string)
     * @return boolean
     */
    public function setGroupPaymentType($gID, $payment)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_paymentType = $payment;
            $update['paymentType'] = $payment;
            $graphModule->updateNodeById($gID, $payment);
        }
    }


    /**
     * This method returns the payment type of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupPaymentType($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['paymentType'];
        }
    }


    /**
     * This method sets the privacy of a group.
     * @access public
     * @param gID (integer)
     * @param privacy (string)
     * @return boolean
     */
    public function setGroupPrivacy($gID, $privacy)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_privacy = $privacy;
            $update['privacy'] = $privacy;
            $graphModule->updateNodeById($gID, $privacy);
        }
    }


    /**
     * This method returns the privacy of a group.
     * @access public
     * @param gID (integer)
     * @return string
     */
    public function getGroupPrivacy($gID)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($gID);
            $group = $apiCall['data'][0][0]['data'];
            return $group['privacy'];
        }
    }

    
    /* This method sets ID of a group.
    * @access public
    * @param gID (integer)
    * @param id (integer)
    * @return boolean
    */
    public function setGroup($gID, $id)
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
}

?>