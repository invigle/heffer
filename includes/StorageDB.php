<?php
require_once(realpath(dirname(__FILE__)) . '/Search.php');

/**
 * @access public
 * @author Grant
 */
class StorageDB extends Search {
	public $_table;
	public $_dataArray;

	/**
	 * @access public
	 * @param aTable
	 * @param aDataArray
	 */
	public function insertRow($aTable, $aDataArray) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aIDArray
	 * @param aTable
	 * @param aFields
	 */
	public function getData($aIDArray, $aTable, $aFields) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aSearchString
	 */
	public function searchDB($aSearchString) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aTable
	 * @param aID
	 */
	public function deleteRow($aTable, $aID) {
		// Not yet implemented
	}

	/**
	 * @access public
	 * @param aTable
	 * @param aDataArray
	 */
	public function updateRow($aTable, $aDataArray) {
		// Not yet implemented
	}
}
?>