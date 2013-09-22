<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class Logging
{
	public $_lastLogin;
	public $_lastIP;
	public $_uID;
	public $_eID;
	public $_gID;
	public $_pID;

	/* The Class Constructor*/
	public function __construct()
	{
		$this->_lastLogin = null;
		$this->_lastIP = null;
		$this->_uID = null;
		$this->_eID = null;
		$this->_gID = null;
		$this->_pID = null;
	}
}

?>