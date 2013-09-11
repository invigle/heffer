<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Group {
	public $_name;
	public $_category;
	public $_shortDescription;
	public $_slogan;
	public $_website;
	public $_gID;
	public $_location;
	public $_memberCount;
	public $_institution;
	public $_isPaid;
	public $_paymentType;
	public $_privacy;
	public $_followerCount;
	public $_groupType;
	public $_profilePicID;

	/**
	 * This method takes as input an array with all the information of a group and adds this group to the GD as a 'group node'.
	 * @access public
	 * @param aGroupArray
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function addGroup($aGroupArray) {
		// Not yet implemented
	}

	/**
	 * This method takes as input the ID of a group and deletes the node that represent this group from the GD.
	 * @access public
	 * @param aGID
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function deleteGroup($aGID) {
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
	public function editGroup($aGroupArray) {
		// Not yet implemented
	}
}
?>