<?php

namespace Invigle;

use Invigle\FrontEndUIs\Header;

require_once("bootstrap.php");

/**
 * @access public
 * @author Grant
 */
class FrontEndUIs
{

    private $_pageTitle;
    public $_header;

    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */
    public function __construct()
    {
        $this->_header = new Header();
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