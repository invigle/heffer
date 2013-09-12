<?php

namespace Invigle;

require_once("bootstrap.php");

/**
 * @access public
 * @author Grant
 */
abstract class FrontEndUIs implements Header
{

    private $_pageTitle;
    
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

    /**
     * Set page title
     * @access public
     * @param apageTitle
     */
    public function setPageTitle($apageTitle)
    {
        $this->_pageTitle = $apageTitle;
    }

    /**
     * @access public
     * @param aPID
     */
    public function renderPage($aPID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aGID
     */
    public function renderGroup($aGID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aUID
     */
    public function renderUser($aUID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aEID
     */
    public function renderEvent($aEID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aUID
     * @param aPassword
     */
    public function login($aUID, $aPassword)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aUID
     */
    public function logout($aUID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aFollowerUID
     * @param aFolloweeUID
     */
    public function addFollower($aFollowerUID, $aFolloweeUID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aFollowerUID
     * @param aFolloweeUID
     */
    public function deleteFollower($aFollowerUID, $aFolloweeUID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     */
    public function register()
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aFriendUID
     * @param aFriendeeUID
     */
    public function addFriend($aFriendUID, $aFriendeeUID)
    {
        // Not yet implemented
    }

    /**
     * @access public
     * @param aFriendUID
     * @param aFriendeeUID
     */
    public function deleteFriend($aFriendUID, $aFriendeeUID)
    {
        // Not yet implemented
    }
}
?>