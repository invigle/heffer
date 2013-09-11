<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Page {
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
	 * This method takes as input an array with all the information of a page and adds this page to the GD as a 'page node'.
	 * @access public
	 * @param aPageArray
	 */
	public function addPage($aPageArray) {
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of a page and deletes the node that represent this page from the GD.
	 * @access public
	 * @param aPID
	 */
	public function deletePage($aPID) {
		// Not yet implemented
	}

	/**
	 * This method edits some of the properties of a page in the GD by updating the current node in the GD with information provided by the pageArray which is the input to the editPage method
	 * @access public
	 * @param aPageArray
	 */
	public function editPage($aPageArray) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aPID
	 */
	public function getPage($aPID) {
		// Not yet implemented
	}

	/**
	 * This method takes the ID of a page and gets 'limit' number of page followers. When the users scrolls down the page, they can see the next set of followers by the method skipping a number of followers indicated by the third argument of the method called 'skip'.
	 * @access public
	 * @param aPID
	 * @param aLimit
	 * @param aSkip
	 */
	public function getFollowers($aPID, $aLimit, $aSkip) {
		// Not yet implemented
	}

	/**
	 * This method takes the ID of a page and retrieves a number of items on the page's timeline determined by the input limit. When the users scrolls down the page, they can see the next set of items by the method skipping a number of items indicated by the third argument of the method called 'skip'.
	 * @access public
	 * @param aPID
	 * @param aLimit
	 * @param aSkip
	 */
	public function getTimeline($aPID, $aLimit, $aSkip) {
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of a page and the ID of the user who adds a post on that page and creates a new 'post node' in the GD.
	 * @access public
	 * @param aPID
	 * @param aUID
	 * @param aTimestamp
	 */
	public function addPost($aPID, $aUID, $aTimestamp) {
		// Not yet implemented
	}
}
?>