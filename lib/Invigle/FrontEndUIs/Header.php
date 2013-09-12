<?php

/**
 * @author Gavin Hanson
 * @copyright 2013
 */
 
 namespace Invigle;
 
 use Invigle\FrontEndUIs;
 
 class Header extends FrontEndUIs {

    /**
     * This function renders the header
	 * @access public
	 */
	public function renderHeader() {
		$html = "<!DOCTYPE HTML>
                    <html>";
        $html .= "<head>
                    <title>{$this->_pageTitle}</title>
                </head>";
        return $html;
	}

    /**
     * This function renders the top nav bar
	 * @access public
	 */
	public function renderTopNav() {
		// Not yet implemented
	}
    
    /**
     * This function renders the top nav bar
	 * @access public
	 */
	public function renderSideBar() {
		// Not yet implemented
	}
    
    
 }

?>