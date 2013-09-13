<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Status {
	public $_status;
	public $_date;
	public $_sID;
	public $_eID;
	public $_gID;
	public $_uID;
	public $_pID;
    private $_nodeType;
    
    public function __construct()
	{
		$this->_nodeType = 'Status';
	}

	/**
	 * This method creates a 'status node' in the GD by getting as an input the date, the ID of the type of node that updated this status; event/group/user/page
	 * @access public
	 * @param aDataArray
	 */
	public function createStatus($aDataArray) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aSID
	 */
	public function deleteStatus($aSID) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aDataArray
	 */
	public function editStatus($aDataArray) {
		// Not yet implemented
	}
}
?>