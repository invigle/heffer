<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User,
    Invigle\Validation,
    Invigle\Status,
    Invigle\Event;

 
/**
 * FrontPage - This renders the front home page
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class FrontPage extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */    
    public function __construct(Language $theLanguage)
    {
        $this->_language = $theLanguage;
        parent::__construct();
        $this->_pageTitle = $this->_language->_frontPage['pageTitle'];
        
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
        echo $this->authenticationLayer();
        echo $this->renderJSLinks();
        echo '</body>';
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    private function authenticationLayer()
    {
        if($this->_loggedin){
            return $this->renderUserHomepage();
        }else{
            return '<div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                '.$this->loginForm($_POST).'
                            </div>
                            <div class="col-md-8">
                                '.$this->registrationForm($_POST).'
                            </div>
                        </div>
                    </div>';
        }
                
    }
    
    private function renderUserHomepage()
    {
        $userModule = new User();
        $eventsModule = new Event();
        $userInfo = $userModule->userDetails();
        
        $friendReqHTML = "";
        $friendRequests = $userModule->userFriendRequests($_SESSION['uid']);
        if(isset($friendRequests)){
            foreach($friendRequests as $req){
                $friendReqHTML.= '<a href="user.php?username='.$req['username'].'">'.$req['firstname'].' '.$req['lastname'].'</a> [<a href="user.php?username='.$req['username'].'&a=acceptfriend">Accept</a>]<br />';
            }
        }else{
            $friendReqHTML = "none";
        }
        
        $invitesHtml = "";
        $inviteRequests = $eventsModule->userInvites($_SESSION['uid']);
        if(isset($inviteRequests)){
            foreach($inviteRequests as $invite){
                $invitesHtml.= ''.$this->_language->_frontPage["your-invited-to"].' <a href="event.php?eventid='.$invite['eventid'].'">'.$invite['name'].'</a> on '.$invite['date'].' [<a href="event.php?eventid='.$invite['eventid'].'&b=attend">Accept</a>]<br>';
            }
        }
        
        if(isset($_POST['saveUserStatus'])){
            //Save the users status, this is going to be done in User.php & Graph.php.
            $statusModule = new Status();
            
            $statusProperties = $_POST;
            $statusProperties['type'] = 'user';
            $statusProperties['oid'] = $_SESSION['uid'];
            
            $statusModule->createStatus($statusProperties);
            $statusHtml = "New Status Stored!";
        }else{
            $statusHtml = "";
        }
        
        return '<div class="container">
                    <b>'.$this->_language->_frontPage["logged-in-as"].' '.$userInfo['firstname'].' '.$userInfo['lastname'].'</b> (<a href="user.php?username='.$userInfo['username'].'">'.$this->_language->_frontPage["my-profile"].'</a> | <a href="accountdetails.php">'.$this->_language->_frontPage["my-details"].'</a> | <a href="?logout=true">Logout</a>)<br />
                    <hr>
                    <form method="POST" action="'.$_SERVER["PHP_SELF"].'">
                        <input type="hidden" name="saveUserStatus" value="true">
                        <b>'.$this->_language->_frontPage["post-a-new-status"].'</b><br />
                        '.$statusHtml.'
                        <input type="text" name="statusData" class="form-control" placeholder="'.$this->_language->_frontPage['post-a-new-status'].'">
                        <input type="submit" name="save" value="Save Status">
                    </form>
                    <hr>
                    '.$this->_language->_frontPage["followers"].': '.$userInfo['followerCount'].'<br>
                    '.$this->_language->_frontPage["friends"].': '.$userInfo['friendCount'].'<br />
                    <hr>
                    <b>Friend Requests ('.count($friendRequests).')</b><br />
                    '.$friendReqHTML.'
                    <hr>
                    <b>Invitations ('.count($inviteRequests).')</b><br />
                    '.$invitesHtml.'
                </div>';
    }
    
    private function loginForm($userInput)
    {
        if(isset($userInput['loginform'])){
            $user = new User();
            $login = $user->loginUser($userInput);
            
            //If the user wants to be remembered then we should set a cookie.
            if(isset($userInput['rememberme'])){
                //Duplicate the session into a Cookie.
            }
            
            
            if(!$login){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["login-failed"].'';
            }else{
                //Refresh Page or redirect to wherever this person was heading before being asked to login.
                if(isset($_SESSION['ROUTE'])){
                    echo '<script>window.location.href = "'.$_SESSION['ROUTE'].'";</script>';
                    unset($_SESSION['ROUTE']);
                }else{
                    return '<script>location.reload();</script>';
                }
                
            }
            
        }
        if(!isset($error)){
            $error = "";
        }

        return ''.$error.'
                <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="loginform" value="submit">
                    <h2>'.$this->_language->_frontPage["loginHere"].'</h2>
                    
                    <label for="email">'.$this->_language->_frontPage["emailaddress"].'</label>
                    <input type="text" class="form-control col-md-12" id="email" name="email" placeholder="">
                    <br />
                    
                    <label for="password">'.$this->_language->_frontPage["password"].'</label>
                    <input type="password" class="form-control col-md-12" id="password" name="password" placeholder="">
                    <br>
                    <input type="checkbox" name="rememberme" value="yes" id="rememberme">
                    <label for="rememberme">Remember Me?</label>
                    <br>
                    <input type="submit" name="login" value="'.$this->_language->_frontPage["login-now"].'">
                </form>';
    }
    
    private function registrationForm($userInput)
    {   
        //Set an empty array so that the form shows blank fields and not missing variable errors.
        $userArray = array(
                        'username'=>'',
                        'password'=>'',
                        'firstname'=>'',
                        'lastname'=>'',
                        'email'=>'',
                        'birthdate'=>'',
                        'gender'=>'',
                        'confirmemail'=>'',
                        'dob_day' => '',
                        'dob_month' => '',
                        'dob_year' => '',
                          );
        
        if(isset($userInput['regform'])){
            $user = new User();
            
            //$userArray Variables that will be stored in neo4j.
            $userArray = array(
                            'username'=>$userInput['username'],
                            'password'=>hash('sha256', "".CONF_SECURITYSALT."".$userInput['password'].""), //SHA256 Hash the users password with SECURITYSALT from Configuration.php
                            'firstname'=>$userInput['firstname'],
                            'lastname'=>$userInput['lastname'],
                            'email'=>$userInput['email'],
                            'birthdate'=>"$userInput[dob_day]-$userInput[dob_month]-$userInput[dob_year]",
                            'gender'=>$userInput['gender'],
                            'sessionid'=>'',
                            'ipaddress'=>$_SERVER['REMOTE_ADDR'],
                            'lastAction'=>time(),
                            'rememberme'=>'',
                            'lastupdate'=>'',
                            'emailvalidated'=>'0',
                            'invitedby'=>'',
                            'location'=>'',
                            'institution'=>'',
                            'relationshipStatus'=>'',
                            'sexualPref'=>'',
                            'profilePicID'=>'',
                            'followerCount'=>'0',
                            'friendCount'=>'0',
                            'privateProfile'=>'no',
                              );
            
            $validationModule = new Validation();
            
            //Check the users date of birth is valid.
            $dobCheck = $validationModule->validateDateOfBirth($_POST['dob_day'], $_POST['dob_month'], $_POST['dob_month']);
            if(!$dobCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$dobCheck['error']].'';
            }
            
            //Check that the users password is valid
            $pwCheck = $validationModule->validatePassword(($_POST['password']), $_POST['password']); //Were using $_POST['password'] twice here because we dont have a confirm field on initial signup.
            if(!$pwCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$pwCheck['error']].'';
            }
            
            //Check that firstname is longer than 2 characters and not numeric.
            $fnameCheck = $validationModule->validateFirstName($_POST['firstname']);
            if(!$fnameCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$fnameCheck['error']].'';
            }
            
            //Check that lastname is longer than 2 characters and not numeric.
            $lnameCheck = $validationModule->validateLastName($_POST['lastname']);
            if(!$lnameCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$lnameCheck['error']].'';
            }
            
            $unCheck = $validationModule->validateUsername($_POST['username']);
            if(!$unCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$unCheck['error']].'';
            }
            
            $emailCheck = $validationModule->validateEmailAddress($_POST['email'], $_POST['confirmemail']);
            if(!$emailCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$emailCheck['error']].'';
            }
            
            //If no error is set yet
            if(!isset($error)){
                //Attempt to add the user.
                $add = $user->addUser($userArray);
            }
            
            //Check for errors in the addUser() function.
            if($add === "email-taken"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation["email-taken"].'';
            }elseif($add === "username-taken"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation["username-taken"].'';
            }
            
            if(isset($error)){
                $userArray['confirmemail'] = $userInput['confirmemail'];
                $userArray['dob_day'] = $userInput['dob_day'];
                $userArray['dob_month'] = $userInput['dob_month'];
                $userArray['dob_year'] = $userInput['dob_year'];
                $userArray['password'] = $userInput['password'];
            }
            
            //At this point either an $error will be set or the API Call will have been successful and $add will contain the new users 'Node ID#' from Neo4J.
            
            if(!isset($error)){
                //Refresh Page.
                $alogin['email'] = $userArray['email'];
                $alogin['password'] = $userInput['password'];
                $login = $user->loginUser($alogin);
                return '<script>window.location.href = "?reg=success";</script>';
            }
            
        }
        
        //Check if $error is set if not then set an empty one to suppress PHP NOTICE errors.
        if(!isset($error)){
            $error = "";
        }
        
        return ''.$error.'
                    <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="regform" value="submit">
                        <h2>'.$this->_language->_frontPage["register"].'</h2>
                        <div class="row">                           
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="firstname" value="'.$userArray['firstname'].'" placeholder="'.$this->_language->_frontPage["firstname"].'">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="lastname" value="'.$userArray['lastname'].'" placeholder="'.$this->_language->_frontPage["lastname"].'">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control col-md-12" name="email" value="'.$userArray['email'].'" placeholder="'.$this->_language->_frontPage["emailaddress"].'">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control col-md-12" name="confirmemail" value="'.$userArray['confirmemail'].'" placeholder="'.$this->_language->_frontPage["confirmemailaddress"].'">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="username" value="'.$userArray['username'].'" placeholder="'.$this->_language->_frontPage["username"].'">
                            </div>
                            <div class="col-md-6">
                                <input type="password" class="form-control col-md-6" name="password" value="'.$userArray['password'].'" placeholder="'.$this->_language->_frontPage["password"].'">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>'.$this->_language->_frontPage["birthdate"].'</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="dob_day" class="form-control" value="'.$userArray['dob_day'].'" placeholder="'.$this->_language->_frontPage["day"].'">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="dob_month" class="form-control" value="'.$userArray['dob_month'].'" placeholder="'.$this->_language->_frontPage["month"].'">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="dob_year" class="form-control" value="'.$userArray['dob_year'].'" placeholder="'.$this->_language->_frontPage["year"].'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>'.$this->_language->_frontPage["gender"].'</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="radio">
                                          <label>
                                              <input type="radio" name="gender" id="gender_male" value="male" checked>
                                              '.$this->_language->_frontPage["male"].'
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="gender" id="gender_female" value="female">
                                                '.$this->_language->_frontPage["female"].'
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="submit" name="register" value="'.$this->_language->_frontPage["registerButton"].'">
                    </form>';
    }
    
 }
 
 ?>