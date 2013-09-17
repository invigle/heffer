<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User,
    Invigle\Graph,
    Invigle\Validation;

 
/**
 * userProfile - this renders the view profile page.
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class userProfile extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */    
    public function __construct(Language $theLanguage)
    {
        $this->_language = $theLanguage;
        parent::__construct();
        $this->_pageTitle = $this->_language->_accountdetails['pageTitle'];
        
        if(isset($_SESSION['sid']) && isset($_SESSION['uid'])){
            //Session is set... But is it a real one?
            $user = new User();
            $this->_loggedin = $user->validateSession();
        }else{
            $this->_loggedin = false;
        }
        
        
        echo $this->renderHeader();
        echo '<body>';
        echo $this->renderTopNav();
        echo "<div class='container'>";
        echo $this->showUserProfile($_GET['username']);
        echo "</div>";
        echo $this->renderJSLinks();
        echo '</body>';
    }
    
    /**
     * This function will render the userProfile page.
     * @param $username
     */
    public function showUserProfile($username)
    {
        return "Showing profile for: $username";
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    
 }
 
 ?>