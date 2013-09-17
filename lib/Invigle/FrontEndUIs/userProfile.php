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
        $userModule = new User();
        $graphModule = new Graph();
        
        $val['query'] = "MATCH n:User WHERE n.username='".$username."' RETURN n;";
		$api = $graphModule->neo4japi('cypher', 'JSONPOST', $val);
        
        if(!isset($api['data'][0])){
            return $this->_language->_userProfile['user-not-found'];
        }else{            
            $usr = explode("/", $api['data'][0][0]['self']);
            $userId = end($usr);
            $userInfo = $api['data'][0][0]['data'];
            
            if(isset($_GET['a'])){
                if($_GET['a'] === "follow"){
                    $userModule->followUser($_SESSION['uid'], $userId);
                    $html = ''.$this->_language->_userProfile['nowfollowing'].' '.$userInfo['firstname'].' '.$userInfo['lastname'].'.';
                }
            }
            
            $html = '<h1>'.$userInfo['firstname'].' '.$userInfo['lastname'].'</h1>';
            if(!empty($userInfo['relationshipStatus'])){
                $html.= 'Relationship Status: '.$userInfo['relationshipStatus'].'<br />';
            }
            
            $html.= '<a href="user.php?username='.$username.'&a=follow">'.$this->_language->_userProfile['follow'].' '.$userInfo['firstname'].'</a> | 
                     <a href="user.php?username='.$username.'&a=befriend">'.$this->_language->_userProfile['addfriend'].'</a>';
            
        
        return $html;
        }
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    
 }
 
 ?>