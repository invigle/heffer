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
	public $_location;
	public $_followerCount;
	public $_pageType;
	public $_profilePicID;

	/**
	 * This method takes as input an array with all the information of a page and 
	 * adds this page to the GD as a 'page node'.
	 * @access public
	 * @param pArray
	 * @return integer
	 */
	public function addPage($pArray)
	{
		//Create the new page in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($pArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$page['query'] = "CREATE (n:Page {" . $queryString . "}) RETURN n;";

		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $page);

		//return the New Page ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$pageId = end($bit);

		return $pageId;
	}

	/** Function to delete a page node given an ID.
	 * @access private
	 * @param pID
	 * @return boolean
	 */
	public function deletePage($pID)
	{
		$graph = new Graph();
		$succ = $graph->deleteNodeByID($pID);
		return $succ;
	}

	/**
	 * This method edits some of the properties of a page in the GD by updating the current node in 
	 * the GD with information provided by the pArray which is the input to the editPage method
	 * @access public
	 * @param pArray
	 * @return boolean
	 */
	public function editPage($pArray)
	{
		$graph = new Graph();
		$succ = $graph->editNodeProperties($pArray);
		return $succ;
	}


	/**
	 * This method takes as inputs a page ID and a location ID and adds the edge to neo4j.
	 * @access public
	 * @param pID, locID
	 * @return boolean
	 */
	public function addPageLocation($pID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->addConnection($pID, $locID, $connectionType);
		return $succ;
	}


	/**
	 * This method takes as inputs a page ID and a location ID and deletes the edge to neo4j.
	 * @access public
	 * @param pID, locID
	 * @return boolean
	 */
	public function deletePageLocation($pID, $locID)
	{
		$graph = new Graph();
		$connectionType = 'LOCATED_AT';
		$succ = $graph->deleteConnection($pID, $locID, $connectionType);
		return $succ;
	}


	/**
	 * @access public
	 * @param id
	 */
	public function getPage($id)
	{
		// Not yet implemented
	}

	/**
	 * This method takes the ID of a page and gets 'limit' number of page followers. When the users scrolls down the page, they can see the next set of followers by the method skipping a number of followers indicated by the third argument of the method called 'skip'.
	 * @access public
	 * @param id
	 * @param limit
	 * @param skip
	 */
	public function getFollowers($id, $limit, $skip)
	{
		// Not yet implemented
	}

	/**
	 * This method takes the ID of a page and retrieves a number of items on the page's timeline determined by the input limit. When the users scrolls down the page, they can see the next set of items by the method skipping a number of items indicated by the third argument of the method called 'skip'.
	 * @access public
	 * @param id
	 * @param limit
	 * @param skip
	 */
	public function getTimeline($id, $limit, $skip)
	{
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of a page and the ID of the user who adds a post on that page and creates a new 'post node' in the GD.
	 * @access public
	 * @param pID
	 * @param uID
	 * @param timestamp
	 */
	public function addPost($pID, $uID, $timestamp)
	{
		// Not yet implemented
	}

	/**
	 * This method returns the name of the page.
	 * @access public
	 * @return string
	 */
	public function getPageName()
	{
		return $this->_name;
	}

	/**
	 * This method sets the name of the page.
	 * @access public
	 * @param name (string)
	 * @return boolean
	 */
	public function setPageName($name)
	{
		$this->_name = $name;
	}

	/**
	 * This method returns the page category.
	 * @access public
	 * @return string
	 */
	public function getPageCategory()
	{
		return $this->_category;
	}

	/**
	 * This method sets the page category.
	 * @access public
	 * @param category (string))
	 * @return boolean
	 */
	public function setPageCategory($category)
	{
		$this->_category = $category;
	}

	/**
	 * This method returns the group short description.
	 * @access public
	 * @return string
	 */
	public function getPageDescription()
	{
		return $this->_shortDescription;
	}

	/**
	 * This method sets the group short description.
	 * @access public
	 * @param description (string)
	 * @return boolean
	 */
	public function setGroupDescription($description)
	{
		$this->_shortDescription = $description;
	}

	/**
	 * This method returns the page's slogan.
	 * @access public
	 * @return string
	 */
	public function getPageSlogan()
	{
		return $this->_slogan;
	}

	/**
	 * This method sets the page's slogan.
	 * @access public
	 * @param slogan (string)
	 * @return boolean
	 */
	public function setPageSlogan($slogan)
	{
		$this->_slogan = $slogan;
	}

	/**
	 * This method returns the page's website url.
	 * @access public
	 * @return url
	 */
	public function getPageWebsite()
	{
		return $this->_website;
	}

	/**
	 * This method sets the page's website url.
	 * @access public
	 * @param website (url)
	 * @return boolean
	 */
	public function setPageWebsite($website)
	{
		$this->_website = $website;
	}

	/**
	 * This method returns the page ID.
	 * @access public
	 * @return integer
	 */
	public function getPageId()
	{
		return $this->_pID;
	}

	/**
	 * This method sets the group ID.
	 * @param id (integer)
	 * @return boolean
	 */
	public function setPageId($id)
	{
		$this->_pID = $id;
	}

	/**
	 * This method returns location of the page.
	 * @access public
	 * @return string
	 */
	public function getPageLocation()
	{
		return $this->_location;
	}

	/**
	 * This method sets the location of the page.
	 * @access public
	 * @param location (string)
	 * @return boolean
	 */
	public function setPageLocation($location)
	{
		$this->_location = $location;
	}

	/**
	 * This method returns the number of followers of the page.
	 * @return integer
	 */
	public function getNumberOfPageFollowers()
	{
		return $this->$_followerCount;
	}

	/**
	 * This method sets the number of followers of the page.
	 * @access public
	 * @param count (integer)
	 * @return boolean
	 */
	public function setNumberOfPageFollowers($count)
	{
		$this->_followerCount = $count;
	}

	/**
	 * This method returns the group type.
	 * @access public
	 * @return string
	 */
	public function getPageType()
	{
		return $this->_pageType;
	}

	/**
	 * This method sets the group type.
	 * @access public
	 * @param type (string))
	 * @return boolean
	 */
	public function setPageType($type)
	{
		$this->_pageType = $type;
	}

	/**
	 * This method returns the ID of the page profile picture.
	 * @access public
	 * @return integer
	 */
	public function getPageProfPicId()
	{
		return $this->_profilePicID;
	}

	/**
	 * This method sets the ID of the page profile picture.
	 * @access public
	 * @param id (integer)
	 * @return boolean
	 */
	public function setPageProfPicId($id)
	{
		$this->_profilePicID = $id;
	}
}

?>