<?php

namespace Invigle;

use Invigle\UITools;

/**
 * FrontEndUIs - Contains the functions needed to render different elements of a page
 * 
 * @package   
 * @author heffer
 * @copyright Gavin Hanson
 * @version 2013
 * @access public
 */

abstract class FrontEndUIs
{

    protected $_pageTitle;
    public $UITools;

    public function __construct()
    {
        $this->UITools = new UITools;
    }

    /**
     * This function renders the header
     * @access public
     */
    public function renderHeader()
    {
        return '<!DOCTYPE HTML>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta name="description" content="">
                        <meta name="author" content="">
                        <title>' . $this->_pageTitle . '</title>
                        <!-- Bootstrap core CSS -->
                        <link href="http://www.getbootstrap.com/dist/css/bootstrap.css" rel="stylesheet">
                        </head>';
                        //<link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    }

    /**
     * This function renders the top nav bar
     * @access public
     */
    public function renderTopNav()
    {
        return '<div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            '.$this->UITools->renderSmallLogo().'
                        </div>
                        <div class="collapse navbar-collapse">
                            <form class="navbar-form navbar-right">
                                <div class="form-group">
                                    <input type="text" placeholder="Search for events, groups, pages and people" class="form-control">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
    }

    /**
     * This function renders the top nav bar
     * @access public
     */
    public function renderSideBar()
    {
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