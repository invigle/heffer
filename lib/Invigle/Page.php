<?php

namespace Invigle;

use Invigle\Graph;

class Page
{
    private $_name;
    private $_category;
    private $_shortDescription;
    private $_slogan;
    private $_website;
    private $_pID;
    private $_locationID;
    private $_followerCount;
    private $_pageType;
    private $_profilePicID;
    private $_adminID;
    private $_hasAdmin;
    private $_profilePic;

    public function __construct()
    {
        $this->_name = null;
        $this->_category = null;
        $this->_shortDescription = null;
        $this->_slogan = null;
        $this->_website = null;
        $this->_pID = null;
        $this->_locationID = null;
        $this->_followerCount = null;
        $this->_pageType = null;
        $this->_profilePicID = null;
        $this->_adminID = null;
        $this->_hasAdmin = null;
        $this->_profilePic = null;
    }

    /**
     * This method sets the name of a page.
     * @access public
     * @param pID (integer)
     * @param name (string)
     * @return boolean
     */
    public function setPageName($pID, $name)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_name = $name;
            $update['name'] = $name;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * This method returns the name of a page.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getPageName($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['name'];
        }
    }

    /**
     * This method sets the short description of a page.
     * @access public
     * @param pID (integer)
     * @param shortDescription (string)
     * @return boolean
     */
    public function setPageDescription($pID, $shortDescription)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $shortDescription;
            $update['shortDescription'] = $shortDescription;
            $graphModule->updateNodeById($pID, $shortDescription);
        }
    }


    /**
     * This method returns the short description of a page.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getPageDescription($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['shortDescription'];
        }
    }

    /**
     * This method sets the slogan of a page.
     * @access public
     * @param pID (integer)
     * @param slogan (string)
     * @return boolean
     */
    public function setPageSlogan($pID, $slogan)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $slogan;
            $update['slogan'] = $slogan;
            $graphModule->updateNodeById($pID, $slogan);
        }
    }


    /**
     * This method returns the slogan of a page.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getPageSlogan($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['slogan'];
        }
    }

    /**
     * This method sets the website of a page.
     * @access public
     * @param pID (integer)
     * @param website (string)
     * @return boolean
     */
    public function setPageWebsite($pID, $website)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $website;
            $update['website'] = $website;
            $graphModule->updateNodeById($pID, $website);
        }
    }


    /**
     * This method returns the website of a page.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getPageWebsite($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['website'];
        }
    }

    /**
     * This method sets the type of a page.
     * @access public
     * @param pID (integer)
     * @param pageType (string)
     * @return boolean
     */
    public function setPageType($pID, $pageType)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_shortDescription = $pageType;
            $update['pageType'] = $pageType;
            $graphModule->updateNodeById($pID, $pageType);
        }
    }


    /**
     * This method returns the type of a page.
     * @access public
     * @param pID (integer)
     * @return string
     */
    public function getPageType($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['pageType'];
        }
    }


    /* This method adds a page node to the GD.
     * @access public
     * @param pArray (array)
     * @return boolean
     */

    public function addPage($pArray)
    {
        $graphModule = new Graph();

        $newPageArray = array(
            'name' => $pArray['name'],
            'category' => $pArray['category'],
            'shortDescription' => $pArray['shortDescription'],
            'slogan' => $pArray['slogan'],
            'website' => $pArray['website'],
            'pID' => '',
            'location' => $pArray['location'],
            'followerCount' => '0',
            'pageType' => $pArray['pageType'],
            'profilePicID' => '',
            'admin' => $pArray['admin'],
            'phID' => '',
        );

        // The array of the new page gets the admin of the page and her/his ID.
        if ($pArray['adminPageAs'] === "user") {
            $newPageArray['ownerType'] = "user";
            $newPageArray['OwnerID'] = $_SESSION['uid'];
        }

        // Creating a page node.
        $pageId = $graphModule->createNode('Page', $newPageArray);

        if ($pArray['adminPageAs'] === "user") {
            // Get the ID of the admin of the page.
            $adminId = $_SESSION['uid'];
            // Set the properties of the createActionProperties array.
            if (isset($eArray['timeline'])) {
                $createActionProperties = array(
                    'actionType' => 'addedPage',
                    'timestamp' => time(),
                    'uid' => $pageId,
                );

                // Create a action node.
                $newPageActionId = $graphModule->createNode('Action', $createActionProperties);

                // Create a new user (the admin of the page) in order to update his/her timeline.
                $userModule = new User();

                // Update the user's timeline by connecting the ID of the admin with the ID of the
                // new action which shows the creation of a new page.
                $userModule->updateUserTimeline($_SESSION['uid'], $newPageActionId);
            }
            $this->_pID = $pageId;
            // Add a connection from the admin node to the page node.
            $graphModule->addConnection($adminId, $this->_pID, 'adminOf');
        }
    }

    /* This method deletes a page node.
     * @access public
     * @param pID (integer)
     * @return boolean
     */
    public function deletePage($pID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule->deleteNodeByID($this->_pID);
            $this->setPage($pID, null);
        }
    }

    /**
     * This method edits some of the properties of a page in the
     * GD by updating the current node with information provided by the pArray.
     * @access public
     * @param pArray (array)
     * @return boolean
     */
    public function editPage($pArray)
    {
        $graphModule = new Graph();

        $newPageArray = array(
            'name' => $pArray['name'],
            'category' => $pArray['category'],
            'shortDescription' => $pArray['shortDescription'],
            'slogan' => $pArray['slogan'],
            'website' => $pArray['website'],
            'pID' => $pArray['phID'],
            'location' => $pArray['location'],
            'followerCount' => '0',
            'pageType' => $pArray['pageType'],
            'profilePicID' => $pArray['profilePicID'],
            'admin' => $pArray['admin'],
        );
        $graphModule->editNodeProperties($newPageArray);
    }


    /* This method sets the ID of a page.
    * @access public
    * @param pID (integer)
    * @param id (integer)
    * @return boolean
    */
    public function setPage($pID, $id)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_pID = $id;
            $update['id'] = $id;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * user ---adminOf---> page
     * @access public
     * @param uID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function addPageAdmin($uID, $pID)
    {
        $graphModule = new Graph();
        $this->_adminID = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_adminID, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->addConnection($this->_adminID, $this->_pID, $connectionType);
            $this->setPageAdmin($pID, $this->_adminID);
        }
    }

    /**
     * user /---adminOf--->/ page
     * @access public
     * @param uID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function deletePageAdmin($uID, $pID)
    {
        $graphModule = new Graph();
        $this->_adminID = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_adminID, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->deleteConnection($this->_adminID, $this->_pID, $connectionType);
            $this->setPageAdmin($pID, null);
        }
    }

    /**
     * user_1 /---adminOf--->/ page
     * user_2 ---adminOf---> page
     * @access public
     * @param uID (integer)
     * @param uID2 (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function changePageAdmin($uID, $uID2, $pID)
    {
        $this->_pID = $pID;
        if (!filter_var($uID, FILTER_VALIDATE_INT)) {
            echo("Previous Admin ID is not valid");
        } elseif (!filter_var($uID2, FILTER_VALIDATE_INT)) {
            echo("New Admin ID is not valid");
        } elseif (!filter_var($this->_gID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $this->deletePageAdmin($uID, $pID);
            $this->addPageAdmin($uID2, $pID);
            $this->setPageAdmin($pID, $uID2);
        }
    }

    /**
     * This method sets the ID of the admin of a page.
     * @access public
     * @param pID (integer)
     * @param admin (integer)
     * @return boolean
     */
    public function setPageAdmin($pID, $admin)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_adminID = $admin;
            $update['admin'] = $admin;
            $graphModule->updateNodeById($pID, $update);
        }
    }


    /**
     * This method returns the ID of the admin of a page.
     * @access public
     * @param pID (integer)
     * @return integer
     */
    public function getPageAdmin($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['admin'];
        }
    }


    /**
     * page ---has---> category
     * @param pID (integer)
     * @param catID (integer)
     * @return boolean
     */
    public function addPageCategory($pID, $catID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_category = $catID;
        if (!filter_var($this->_category, FILTER_VALIDATE_INT)) {
            echo("Category ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'has';
            $graphModule->addConnection($this->_pID, $this->_category, $connectionType);
            $this->setPageCategory($pID, $catID);
        }
    }

    /**
     * page /---has--->/ category
     * @access public
     * @param pID (integer)
     * @param catID (integer)
     * @return boolean
     */
    public function deletePageCategory($pID, $catID)
    {
        $graphModule = new Graph();
        $this->_category = $catID;
        $this->_pID = $pID;
        if (!filter_var($this->_category, FILTER_VALIDATE_INT)) {
            echo("Category ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'has';
            $graphModule->deleteConnection($this->_pID, $this->_category, $connectionType);
            $this->setPageCategory($pID, null);
        }
    }

    /**
     * This method sets the category of the page.
     * @access public
     * @param pID (integer)
     * @param category (integer)
     * @return boolean
     */
    public function setPageCategory($pID, $category)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_category = $category;
            $update['category'] = $category;
            $graphModule->updateNodeById($pID, $update);
        }
    }


    /**
     * This method returns the ID of the category of a page.
     * @access public
     * @param pID (integer)
     * @return integer
     */
    public function getPageCategory($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Group ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['category'];
        }
    }

    /**
     * profile_picture ---relatedTo---> page
     * @access public
     * @param phID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function addPageProfilePic($phID, $pID)
    {
        $graphModule = new Graph();
        $this->_profilePic = $phID;
        if (!filter_var($this->_profilePic, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->addConnection($this->_profilePic, $pID, $connectionType);
            $this->setPageProfilePic($pID, $phID);
        }
    }

    /**
     * profile_picture /---relatedTo--->/ page
     * @access public
     * @param phID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function deletePageProfilePic($phID, $pID)
    {
        $graphModule = new Graph();
        $this->_profilePic = $phID;
        if (!filter_var($this->_profilePic, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->deleteConnection($this->_profilePic, $pID, $connectionType);
            $this->setPageProfilePic($pID, null);
        }
    }

    /**
     * This method sets the ID of the profile picture of a page.
     * @access public
     * @param pID (integer)
     * @param photo (integer)
     * @return boolean
     */
    public function setPageProfilePic($pID, $photo)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_profilePic = $photo;
            $update['phID'] = $this->_profilePic;
            $graphModule->updateNodeById($pID, $update);
        }
    }


    /**
     * This method returns the ID of a profile picture of a page.
     * @access public
     * @param pID (integer)
     * @return integer
     */
    public function getPageProfilePic($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['phID'];
        }
    }

    /**
     * page ---locatedAt---> location
     * @access public
     * @param pID (integer)
     * @param locID (integer)
     * @return boolean
     */
    public function addPageLocation($pID, $locID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_locationID = $locID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_locationID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->addConnection($this->_pID, $this->_locationID, $connectionType);
            $this->setPageLocation($pID, $locID);
        }
    }

    /**
     * page /---locatedAt--->/ location
     * @access public
     * @param pID (integer)
     * @param locID (integer)
     * @return boolean
     */
    public function deletePageLocation($pID, $locID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_locationID = $locID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_locationID, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->deleteConnection($this->_pID, $this->_locationID, $connectionType);
            $this->setPageLocation($pID, null);
        }
    }

    /**
     * page /---locatedAt--->/ location_1
     * page ---locatedAt---> location_2
     * @access public
     * @param pID (integer)
     * @param locID (integer)
     * @param locID2 (integer)
     * @return boolean
     */

    public function changePageLocation($pID, $locID, $locID2)
    {
        $this->_pID = $pID;
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Previous Location ID is not valid");
        } elseif (!filter_var($locID2, FILTER_VALIDATE_INT)) {
            echo("New Location ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $this->deletePageLocation($pID, $locID);
            $this->addPageLocation($pID, $locID2);
            $this->setPageLocation($pID, $locID2);
        }
    }


    /**
     * This method sets the location of a page.
     * @access public
     * @param pID (integer)
     * @param location (integer)
     * @return boolean
     */
    public function setPageLocation($pID, $location)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_locationID = $location;
            $update['location'] = $location;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * This method returns the ID of the location of a page.
     * @access public
     * @param pID (integer)
     * @return integer
     */
    public function getPageLocation($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['location'];
        }
    }

    /**
     * user ---followerOf---> page
     * @access public
     * @param uID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function addPageFollower($uID, $pID)
    {
        $graphModule = new Graph();
        $currPageFollowers = $this->getNumberOfPageFollowers($pID);
        $this->_pID = $pID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->addConnection($uID, $this->_pID, $connectionType);
            $this->setNumberOfPageFollowers($this->_pID, $currPageFollowers + 1);
        }
    }

    /**
     * user /---followerOf--->/ page
     * @access public
     * @param uID (integer)
     * @param pID (integer)
     * @return boolean
     */
    public function deletePageFollower($uID, $pID)
    {
        $graphModule = new Graph();
        $currPageFollowers = $this->getNumberOfPageFollowers($pID);
        $this->_pID = $pID;
        if (!filter_var($uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->deleteConnection($uID, $this->_pID, $connectionType);
            $this->setNumberOfPageFollowers($this->_pID, $currPageFollowers - 1);
        }
    }

    /**
     * This method returns the number of followers of a page.
     * @access public
     * @param pID (integer)
     * @return float
     */
    public function getNumberOfPageFollowers($pID)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
            return false;
        } else {
            $graphModule = new Graph();
            $apiCall = $graphModule->selectNodeById($pID);
            $page = $apiCall['data'][0][0]['data'];
            return $page['followerCount'];
        }
    }

    /**
     * This method sets the number of followers of a page.
     * @access public
     * @param pID (integer)
     * @param followers (double)
     * @return boolean
     */
    public function setNumberOfPageFollowers($pID, $followers)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_followerCount = $followers;
            $update['followerCount'] = $followers;
            $graphModule->updateNodeById($pID, $update);
        }
    }
}

?>