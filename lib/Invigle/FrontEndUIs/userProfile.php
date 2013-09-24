<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User,
    Invigle\Timeline,
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
        $this->_pageTitle = $this->_language->_userProfile['pageTitle'];
        
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
        $timelineModule = new Timeline();
        
        $val['query'] = "MATCH n:User WHERE n.username='".$username."' RETURN n;";
		$api = $graphModule->neo4japi('cypher', 'JSONPOST', $val);       
        
        if(!isset($api['data'][0])){
            return $this->_language->_userProfile['user-not-found'];
        }else{            
            $html = "";
            
            $usr = explode("/", $api['data'][0][0]['self']);
            $userId = end($usr);
            $userInfo = $api['data'][0][0]['data'];
            
            if(isset($_GET['a'])){
                if($_GET['a'] === "follow"){
                    if(!$userModule->checkFollowStatus($_SESSION['uid'], $userId)){
                        $userModule->followUser($_SESSION['uid'], $userId);
                        $html.= ''.$this->_language->_userProfile['nowfollowing'].' '.$userInfo['firstname'].' '.$userInfo['lastname'].'.';
                    }else{
                        $html.= $this->_language->_userProfile['error-cannot-follow'];
                    }
                
                }elseif($_GET['a'] === "befriend"){
                    if(!$userModule->checkFriendStatus($_SESSION['uid'], $userId)){
                        $userModule->addFriend($_SESSION['uid'], $userId);
                        $html.= $this->_language->_userProfile['friend-request-sent'];
                    }else{
                        $html.= $this->_language->_userProfile['cannot-befriend'];
                    }
                
                }elseif($_GET['a'] === "acceptfriend"){
                    $userModule->acceptFriend($_SESSION['uid'], $userId);
                    $html.= ''.$this->_language->_userProfile['you-are-now-friends-with'].' '.$userInfo['firstname'].'';
                }
            }
            
            $html.= '<h1>'.$userInfo['firstname'].' '.$userInfo['lastname'].'</h1>';
            if(!empty($userInfo['relationshipStatus'])){
                $html.= 'Relationship Status: '.$userInfo['relationshipStatus'].'<br />';
            }
            
            if($this->_loggedin){
                if($userId !== $_SESSION['uid']){
                    if(!$userModule->checkFollowStatus($_SESSION['uid'], $userId)){
                        $html.= '<a href="user.php?username='.$username.'&a=follow">'.$this->_language->_userProfile['follow'].' '.$userInfo['firstname'].'</a> | ';
                    }else{
                        $html.= ''.$this->_language->_userProfile['already-following'].' | ';
                    }
                    if(!$userModule->checkFriendStatus($_SESSION['uid'], $userId)){
                        $html.= '<a href="user.php?username='.$username.'&a=befriend">'.$this->_language->_userProfile['addfriend'].'</a>';
                    }else{
                        $html.= ''.$this->_language->_userProfile['already-friends'].'';
                    }
                }
            }
            
            //Handle the users followers
            $followers = $userModule->userFollowersList($userId);
            $followersHtml = "";
            if(isset($followers)){
                foreach($followers as $follower){
                    $followersHtml.= '<a href="user.php?username='.$follower['username'].'">'.$follower['firstname'].' '.$follower['lastname'].'</a><br />';
                }
            }else{
                $followersHtml.= $this->_language->_userProfile['nobody'];
            }
            
            //Handle the users followees
            $followees = $userModule->userFollowingList($userId);
            $followeesHtml = "";
            if(isset($followees)){
                foreach($followees as $followee){
                    $followeesHtml.= '<a href="user.php?username='.$followee['username'].'">'.$followee['firstname'].' '.$followee['lastname'].'</a><br />';
                }
            }else{
                $followeesHtml.= $this->_language->_userProfile['nobody'];
            }
            
            //Handle the users friends list
            $friends = $userModule->getFriendsList($userId);
            $friendsHtml = "";
            if(isset($friends)){
                foreach($friends as $friend){
                    $friendsHtml.= '<a href="user.php?username='.$friend['username'].'">'.$friend['firstname'].' '.$friend['lastname'].'</a><br />';
                }
            }
            
            $timeline = $timelineModule->createTimeline($userId);
            
            $html.= '<br /><br />
                     <div class="row">
                        <div class="col-md-3">
                            <b>'.$userInfo['firstname'].' '.$this->_language->_userProfile['is-friends-with'].' ('.count($friends).')</b><br />
                            '.$friendsHtml.'
                            <br /><br />
                            <b>'.$userInfo['firstname'].' '.$this->_language->_userProfile['being-followed-by'].' ('.count($followers).')</b><br />
                            '.$followersHtml.'
                            <br /><br />
                            <b>'.$userInfo['firstname'].' '.$this->_language->_userProfile['is-following'].' ('.count($followees).')</b><br />
                            '.$followeesHtml.'
                        </div>
                        <div class="col-md-9">
                            <b>'.$this->_language->_timeline['timeline'].'</b><br />
                            '.$timeline.'
                        </div>
                     </div>';
            

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