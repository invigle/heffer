<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User;

 
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
    
    private function loginForm($_POST)
    {
        if(isset($_POST['loginform'])){
            $user = new User();
            $login = $user->loginUser($_POST);
            
            //If the user wants to be remembered then we should set a cookie.
            if(isset($_POST['rememberme'])){
                //Duplicate the session into a Cookie.
            }
            
            
            if(!$login){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["login-failed"].'';
            }
            
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
    
    private function registrationForm($_POST)
    {   
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
        
        if(isset($_POST['regform'])){
            $user = new User();
            
            //$userArray Variables that will be stored in neo4j.
            $userArray = array(
                            'username'=>$_POST['username'],
                            'password'=>hash('sha256', CONF_SECURITYSALT.$_POST['password']), //SHA256 Hash the users password with SECURITYSALT from Configuration.php
                            'firstname'=>$_POST['firstname'],
                            'lastname'=>$_POST['lastname'],
                            'email'=>$_POST['email'],
                            'birthdate'=>"$_POST[dob_day]-$_POST[dob_month]-$_POST[dob_year]",
                            'gender'=>$_POST['gender'],
                            'sessionid'=>'',
                            'ipaddress'=>$_SERVER['REMOTE_ADDR'],
                            'lastAction'=>time(),
                            'rememberme'=>''
                              );
            
            //Check that the Date of Birth fields are numeric values only.
            if(!is_numeric($_POST['dob_day']) || !is_numeric($_POST['dob_month']) || !is_numeric($_POST['dob_year'])){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["dob-invalid"].'';
            }
            
            //Check that the users password is more than 6 characters long.
            if(strlen($_POST['password'] <= "6")){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["pw-too-short"].'';
            }
            
            //Check that firstname and lastname are more than 2 characters long and are not numeric.
            if(strlen($_POST['firstname']) < "2"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["firstname-too-short"].'';
            }
            if(is_numeric($_POST['firstname'])){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["name-is-numeric"].'';
            }
            if(strlen($_POST['lastname']) < "2"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["lastname-too-short"].'';
            }
            if(is_numeric($_POST['lastname'])){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["name-is-numeric"].'';
            }
            
            //If no error is set yet
            if(!isset($error)){
                //Attempt to add the user.
                $add = $user->addUser($userArray);
            }
            
            //Check for errors in the addUser() function.
            if($add === "email-taken"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["email-taken"].'';
            }elseif($add === "username-taken"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["username-taken"].'';
            }elseif($add === "username-invalid"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["username-invalid"].'';
            }elseif($add === "email-invalid"){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["email-invalid"].'';
            }
            
            if(isset($error)){
                $userArray['confirmemail'] = $_POST['confirmemail'];
                $userArray['dob_day'] = $_POST['dob_day'];
                $userArray['dob_month'] = $_POST['dob_month'];
                $userArray['dob_year'] = $_POST['dob_year'];
                $userArray['password'] = $_POST['password'];
            }
            
            //At this point either an $error will be set or the API Call will have been successful and $add will contain the new users 'Node ID#' from Neo4J.
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