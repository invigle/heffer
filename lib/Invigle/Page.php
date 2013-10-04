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

    //todo: manos to continue from here










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










































//    /** Function to delete a page node given an ID.
//	 * @access private
//	 * @param pID
//	 * @return boolean
//	 */
//	public function deletePage($pID)
//	{
//		$graph = new Graph();
//		$succ = $graph->deleteNodeByID($pID);
//		if (!$succ)
//		{
//			throw new Exception("Page $pID could not be deleted.");
//		}
//	}
//
//	/**
//	 * This method edits some of the properties of a page in the GD by updating the current node in
//	 * the GD with information provided by the pArray which is the input to the editPage method
//	 * @access public
//	 * @param pArray
//	 * @return boolean
//	 */
//	public function editPage($pArray)
//	{
//		$graph = new Graph();
//		$succ = $graph->editNodeProperties($pArray);
//		return $succ;
//	}
//
//
//	/**
//	 * This method takes as inputs a page ID and a location ID and adds the edge to neo4j.
//	 * @access public
//	 * @param pID, locID
//	 * @return boolean
//	 */
//	public function addPageLocation($pID, $locID)
//	{
//		$graph = new Graph();
//		$connectionType = 'LOCATED_AT';
//		$succ = $graph->addConnection($pID, $locID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("Location $locID of page $pID could not be added.");
//		}
//		$this->_location = $locID;
//	}
//
//
//	/**
//	 * This method takes as inputs a page ID and a location ID and deletes the edge to neo4j.
//	 * @access public
//	 * @param pID, locID
//	 * @return boolean
//	 */
//	public function deletePageLocation($pID, $locID)
//	{
//		$graph = new Graph();
//		$connectionType = 'LOCATED_AT';
//		$succ = $graph->deleteConnection($pID, $locID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("Location $locID of page $pID could not be deleted.");
//		}
//		$this->_location = null;
//	}
//
//	/**
//	 * This method takes as inputs a page ID and a event ID and adds the edge to neo4j.
//	 * @access public
//	 * @param pID, eID
//	 * @return boolean
//	 */
//	public function addPageEvent($pID, $eID)
//	{
//		$graph = new Graph();
//		$connectionType = 'ORGANISER_OF';
//		$succ = $graph->addConnection($pID, $eID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("Event $eID organized by page $pID could not be added.");
//		}
//		$this->_eID = $eID;
//	}
//
//
//	/**
//	 * This method takes as inputs a page ID and a event ID and deletes the edge from neo4j.
//	 * @access public
//	 * @param pID, eID
//	 * @return boolean
//	 */
//	public function deletePageEvent($pID, $eID)
//	{
//		$graph = new Graph();
//		$connectionType = 'ORGANISER_OF';
//		$succ = $graph->deleteConnection($pID, $eID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("Event $eID organized by page $pID could not be deleted.");
//		}
//		$this->_eID = null;
//	}
//
//	/**
//	 * This method takes as inputs a user ID and a page ID and adds a FOLLOWER_OF edge to neo4j.
//	 * @access public
//	 * @param uID, pID
//	 * @return boolean
//	 */
//	public function addPageFollower($uID, $pID)
//	{
//		$graph = new Graph();
//		$connectionType = 'FOLLOWER_OF';
//		$succ = $graph->addConnection($uID, $pID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("User $uID could not be added as follower of page $pID.");
//		}
//		$this->_followerCount += 1;
//	}
//
//	public function deletePageFollower($uID, $pID)
//	{
//		$graph = new Graph();
//		$connectionType = 'FOLLOWER_OF';
//		$succ = $graph->deleteConnection($uID, $pID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("User $uID could not be deleted from followers of page $pID.");
//		}
//		$this->_followerCount -= 1;
//	}
//
//	/**
//	 * This method takes as inputs a user ID and a page ID and adds a ADMIN_OF edge to neo4j.
//	 * @access public
//	 * @param uID, pID
//	 * @return boolean
//	 */
//	public function addPageAdmin($uID, $pID)
//	{
//		$graph = new Graph();
//		$connectionType = 'ADMIN_OF';
//		$succ = $graph->addConnection($uID, $pID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("User $uID could not be added as admin of page $pID.");
//		}
//		$_hasAdmin = true;
//	}
//
//	public function deletePageAdmin($uID, $pID)
//	{
//		$graph = new Graph();
//		$connectionType = 'ADMIN_OF';
//		$succ = $graph->deleteConnection($uID, $pID, $connectionType);
//		if (!$succ)
//		{
//			throw new Exception("User $uID could not be removed as admin of page $pID.");
//		}
//		$this->_hasAdmin = false;
//	}
//
//	public function changePageAdmin($uID, $uID2, $pID)
//	{
//		$graph = new Graph();
//		$this->deletePageAdmin($uID, $pID);
//		$this->addPageAdmin($uID2, $pID);
//	}
//
//
//	/**
//	 * This method takes the ID of a page and gets 'limit' number of page followers.
//	 * When the users scrolls down the page, they can see the next set of followers by the method
//	 * skipping a number of followers indicated by the third argument of the method called 'skip'.
//	 * @access public
//	 * @param id
//	 * @param limit
//	 * @param skip
//	 */
//	public function getFollowers($pID, $limit, $skip)
//	{
//		// Not yet implemented
//	}
//
//	/**
//	 * This method takes the ID of a page and retrieves a number of items on the page's
//	 * timeline determined by the input limit.
//	 * When the users scrolls down the page, they can see the next set of items by the
//	 * method skipping a number of items indicated by the third argument of the method called 'skip'.
//	 * @access public
//	 * @param id
//	 * @param limit
//	 * @param skip
//	 */
//	public function getTimeline($id, $limit, $skip)
//	{
//		// Not yet implemented
//	}
//
//	/**
//	 * This method takes as input the ID of a page and the ID of the user who adds a post on that page and creates a new 'post node' in the GD.
//	 * @access public
//	 * @param pID
//	 * @param uID
//	 * @param timestamp
//	 */
//	public function addPost($pID, $uID, $timestamp)
//	{
//		// Not yet implemented
//	}
//
//	/**********************************************************/
//	/** SETS and GETS *****************************/
//	/**********************************************************/
//	/**
//	 * This method returns the name of the page.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageName()
//	{
//		return $this->_name;
//	}
//
//	/**
//	 * This method sets the name of the page.
//	 * @access public
//	 * @param name (string)
//	 * @return boolean
//	 */
//	public function setPageName($name)
//	{
//		$this->_name = $name;
//	}
//
//	/**
//	 * This method returns the page category.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageCategory()
//	{
//		return $this->_category;
//	}
//
//	/**
//	 * This method sets the page category.
//	 * @access public
//	 * @param category (string))
//	 * @return boolean
//	 */
//	public function setPageCategory($category)
//	{
//		$this->_category = $category;
//	}
//
//	/**
//	 * This method returns the group short description.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageDescription()
//	{
//		return $this->_shortDescription;
//	}
//
//	/**
//	 * This method sets the group short description.
//	 * @access public
//	 * @param description (string)
//	 * @return boolean
//	 */
//	public function setGroupDescription($description)
//	{
//		$this->_shortDescription = $description;
//	}
//
//	/**
//	 * This method returns the page's slogan.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageSlogan()
//	{
//		return $this->_slogan;
//	}
//
//	/**
//	 * This method sets the page's slogan.
//	 * @access public
//	 * @param slogan (string)
//	 * @return boolean
//	 */
//	public function setPageSlogan($slogan)
//	{
//		$this->_slogan = $slogan;
//	}
//
//	/**
//	 * This method returns the page's website url.
//	 * @access public
//	 * @return url
//	 */
//	public function getPageWebsite()
//	{
//		return $this->_website;
//	}
//
//	/**
//	 * This method sets the page's website url.
//	 * @access public
//	 * @param website (url)
//	 * @return boolean
//	 */
//	public function setPageWebsite($website)
//	{
//		$this->_website = $website;
//	}
//
//	/**
//	 * This method returns the page ID.
//	 * @access public
//	 * @return integer
//	 */
//	public function getPageId()
//	{
//		return $this->_pID;
//	}
//
//	/**
//	 * This method sets the group ID.
//	 * @param id (integer)
//	 * @return boolean
//	 */
//	public function setPageId($id)
//	{
//		$this->_pID = $id;
//	}
//
//	/**
//	 * This method returns location of the page.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageLocation()
//	{
//		return $this->_location;
//	}
//
//	/**
//	 * This method sets the location of the page.
//	 * @access public
//	 * @param location (string)
//	 * @return boolean
//	 */
//	public function setPageLocation($location)
//	{
//		$this->_location = $location;
//	}
//
//	/**
//	 * This method returns the number of followers of the page.
//	 * @return integer
//	 */
//	public function getNumberOfPageFollowers()
//	{
//		return $this->$_followerCount;
//	}
//
//	/**
//	 * This method sets the number of followers of the page.
//	 * @access public
//	 * @param count (integer)
//	 * @return boolean
//	 */
//	public function setNumberOfPageFollowers($count)
//	{
//		$this->_followerCount = $count;
//	}
//
//	/**
//	 * This method returns the group type.
//	 * @access public
//	 * @return string
//	 */
//	public function getPageType()
//	{
//		return $this->_pageType;
//	}
//
//	/**
//	 * This method sets the group type.
//	 * @access public
//	 * @param type (string))
//	 * @return boolean
//	 */
//	public function setPageType($type)
//	{
//		$this->_pageType = $type;
//	}
//
//	/**
//	 * This method returns the ID of the page profile picture.
//	 * @access public
//	 * @return integer
//	 */
//	public function getPageProfPicId()
//	{
//		return $this->_profilePicID;
//	}
//
//	/**
//	 * This method sets the ID of the page profile picture.
//	 * @access public
//	 * @param id (integer)
//	 * @return boolean
//	 */
//	public function setPageProfPicId($id)
//	{
//		$this->_profilePicID = $id;
//	}
}

?>