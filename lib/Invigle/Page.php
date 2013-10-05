<?php

namespace Invigle;
use Invigle\Graph;

/**
 * @access public
 * @author Manos
 */
class Page
{
	public $_name;
	public $_category;
	public $_shortDescription;
	public $_slogan;
	public $_website;
	public $_pID;
	public $_eID;
	public $_location;
	public $_followerCount;
	public $_pageType;
	public $_profilePicID;
	private $_nodeType;
    private $_admin;
	private $_hasAdmin;
    public $_pHID;

	public function __construct()
	{
		$this->_name = null;
		$this->_category = null;
		$this->_shortDescription = null;
		$this->_slogan = null;
		$this->_website = null;
		$this->_pID = null;
		$this->_location = null;
		$this->_followerCount = null;
		$this->_pageType = null;
		$this->_profilePicID = null;
		$this->_nodeType = 'Page';
        $this->_admin = null;
		$this->_hasAdmin = null;
        $this->_pHID = null;
	}

    /* This method takes as input an array with all the information of a page and
     * adds this page to the GD as a page node.
     * @access public
     * @param pArray
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
            $this->_gID = $pageId;
            // Add a connection from the admin node to the page node.
            $graphModule->addConnection($adminId, $this->_pID, 'adminOf');
        }
    }

    /* This method deletes a page node given an ID.
     * @access public
     * @param gID
     */
    public function deletePage($pID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule->deleteNodeByID($this->_pID);
            $this->setPageId($pID, null);
        }
    }

    /**
     * This method edits some of the properties of a page in the GD by updating the current node in
     * the GD with information provided by the pArray.
     * @access public
     * @param gArray
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
   * @param pID, id
   * @return boolean
   */
    public function setPageId($pID, $id)
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
     * This method takes as inputs a user ID and a page ID and adds a adminOf edge to neo4j.
     * @access public
     * @param uID, pID
     * @return boolean
     */
    public function addPageAdmin($uID, $pID)
    {
        $graphModule = new Graph();
        $this->_admin = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_admin, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->addConnection($this->_admin, $this->_pID, $connectionType);
            $this->setPageAdminId($pID, $this->_admin);
        }
    }

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
            $this->setPageAdminId($pID, $uID2);
        }
    }

    public function deletePageAdmin($uID, $pID)
    {
        $graphModule = new Graph();
        $this->_admin = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_admin, FILTER_VALIDATE_INT)) {
            echo("Admin ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'adminOf';
            $graphModule->deleteConnection($this->_admin, $this->_pID, $connectionType);
            $this->setPageAdminId($pID, null);
        }
    }

    /**
     * This method returns the ID of the admin of a page.
     * @access public
     * @return integer
     */
    public function getPageAdminId($pID)
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
     * This method sets the ID of the admin of the page.
     * @access public
     * @param id (integer)
     * @return boolean
     */
    public function setPageAdminId($pID, $admin)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_admin = $admin;
            $update['admin'] = $admin;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * This method takes as inputs a page ID the ID of the category and then
     * it adds a Has edge to neo4j.
     * @access public
     * @param pID, catID
     */
    public function addPageCategory($catID, $pID)
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
            $graphModule->addConnection($this->_category, $this->_pID, $connectionType);
            $this->setPageCategoryId($pID, $catID);
        }
    }

    /**
     *  This method takes as inputs a page ID the ID of the category and then it adds a HAS edge from neo4j.
     * @access public
     * @param pID, catID
     */
    public function deletePageCategory($catID, $pID)
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
            $graphModule->deleteConnection($this->_category, $this->_pID, $connectionType);
            $this->setPageCategoryId($pID, null);
        }
    }


    /**
     * This method returns the ID of the category of the page.
     * @access public
     * @return integer
     */
    public function getPageCategoryId($pID)
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
     * This method sets the category of the page.
     * @access public
     * @param location (string)
     * @return boolean
     */
    public function setPageCategoryId($pID, $category)
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
     * This method takes as inputs a photo ID, the ID of a page and adds a relatedTo edge to neo4j.
     * @access public
     * @param phID, pID
     */
    public function addPagePhoto($phID, $pID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->addConnection($this->_pHID, $pID, $connectionType);
            $this->setPagePhotoId($pID, $phID);
        }
    }

    /**
     * This method takes as inputs a photo ID, the ID of a page and deletes a
     * relatedTo edge from neo4j.
     * @access public
     * @param phID, pID
     */
    public function deletePagePhoto($phID, $pID)
    {
        $graphModule = new Graph();
        $this->_pHID = $phID;
        if (!filter_var($this->_pHID, FILTER_VALIDATE_INT)) {
            echo("Photo ID is not valid");
        } else {
            $connectionType = 'relatedTo';
            $graphModule->deleteConnection($this->_pHID, $pID, $connectionType);
            $this->setPagePhotoId($pID, null);
        }
    }

    /**
     * This method returns the ID of a photo of the page.
     * @access public
     * @return integer
     */
    public function getPagePhotoId($pID)
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
     * This method sets the ID of the photo of a page.
     * @access public
     * @param id (integer)
     * @return boolean
     */
    public function setPagePhotoId($pID, $photo)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_pHID = $photo;
            $update['phID'] = $this->_pHID;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * This method takes as inputs a page ID and a location ID and adds a locatedAt edge to neo4j.
     * @access public
     * @param pID, locID
     */
    public function addPageLocation($pID, $locID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_location = $locID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_location, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->addConnection($this->_pID, $this->_location, $connectionType);
            $this->setPageLocationId($pID, $locID);
        }
    }


    public function changePageLocation($locID, $locID2, $pID)
    {
        $this->_pID = $pID;
        if (!filter_var($locID, FILTER_VALIDATE_INT)) {
            echo("Previous Location ID is not valid");
        } elseif (!filter_var($locID2, FILTER_VALIDATE_INT)) {
            echo("New Location ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $this->deletePageLocation($locID, $pID);
            $this->addPageLocation($locID2, $pID);
            $this->setPageLocationId($pID, $locID2);
        }
    }

    /**
     * This method takes as inputs a page ID and a location ID and deletes a locatedAt edge from neo4j.
     * @access public
     * @param pID, locID
     */
    public function deletePageLocation($pID, $locID)
    {
        $graphModule = new Graph();
        $this->_pID = $pID;
        $this->_location = $locID;
        if (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } elseif (!filter_var($this->_location, FILTER_VALIDATE_INT)) {
            echo("Location ID is not valid");
        } else {
            $connectionType = 'locatedAt';
            $graphModule->deleteConnection($this->_pID, $this->_location, $connectionType);
            $this->setPageLocationId($pID, null);
        }
    }

    /**
     * This method returns the ID of the location of the page.
     * @access public
     * @return integer
     */
    public function getPageLocationId($pID)
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
     * This method sets the location of the page.
     * @access public
     * @param location (string)
     * @return boolean
     */
    public function setPageLocationId($pID, $location)
    {
        if (!filter_var($pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $graphModule = new Graph();
            $this->_location = $location;
            $update['location'] = $location;
            $graphModule->updateNodeById($pID, $update);
        }
    }

    /**
     * This method takes as inputs a user ID and a page ID and adds a followerOf edge to neo4j.
     * @access public
     * @param uID, gID
     * @return boolean
     */
    public function addPageFollower($uID, $pID)
    {
        $graphModule = new Graph();
        $currPageFollowers = $this->getNumberOfPageFollowers($pID);
        $this->_uID = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->addConnection($this->_uID, $this->_pID, $connectionType);
            $this->setNumberOfPageFollowers($pID, $currPageFollowers + 1);
        }
    }

    public function deletePageFollower($uID, $pID)
    {
        $graphModule = new Graph();
        $currPageFollowers = $this->getNumberOfPageFollowers($pID);
        $this->_uID = $uID;
        $this->_pID = $pID;
        if (!filter_var($this->_uID, FILTER_VALIDATE_INT)) {
            echo("Follower ID is not valid");
        } elseif (!filter_var($this->_pID, FILTER_VALIDATE_INT)) {
            echo("Page ID is not valid");
        } else {
            $connectionType = 'followerOf';
            $graphModule->deleteConnection($this->_uID, $this->_pID, $connectionType);
            $this->setNumberOfPageFollowers($pID, $currPageFollowers - 1);
        }
    }

    /**
     * This method returns the number of followers of the page.
     * @access public
     * @return integer
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
     * This method sets the number of followers of the page.
     * @access public
     * @param followers (integer)
     * @return boolean
     */
    public function setNumberOfPageFollowers($gID, $followers)
    {
        if (!filter_var($gID, FILTER_VALIDATE_INT)) {
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