<?php
require_once(realpath(dirname(__FILE__)) . '/Search.php');

/**
 * @access public
 * @author Grant
 */
class Graph extends Search {
	public $_friends;
	public $_frendsOfFriends;
	public $_event;
	public $_group;
	public $_page;
	public $_user;
	public $_location;
	public $_rangeLoc;
	public $_isPhoto;
	public $_university;
	public $_sourceID;
	public $_edgeType;
	public $_resultLimit;
	public $_skip;

	/**
	 * @access public
	 * @param aTermsArray
	 */
	public function graphSearch($aTermsArray) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID
	 */
	public function addNode($aID) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID
	 */
	public function deleteNode($aID) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function addConnection($aID1, $aID2, $aType) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID1
	 * @param aID2
	 * @param aType
	 */
	public function deleteConnection($aID1, $aID2, $aType) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID
	 * @param aType
	 * @param aSkip
	 * @param aLimit
	 */
	public function listNodes($aID, $aType, $aSkip, $aLimit) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aID
	 */
	public function getIndex($aID) {
		// Not yet implemented
	}
}
?>