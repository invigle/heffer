<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User,
    Invigle\Graph,
    Invigle\Validation;

 
/**
 * accountDetails - this renders the account details pages.
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class accountDetails extends FrontEndUIs {
    
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
        if(!$this->_loggedin){
            $_SESSION['ROUTE'] = $_SERVER['PHP_SELF'];
            echo '<script>window.location.href = "/";</script>';
        }else{
            echo $this->userDetails();
        }
        echo $this->renderJSLinks();
        echo '</body>';
    }
    
    public function userDetails() {
        return '<div class="container">
                    '.$this->changePassword().'
                    '.$this->setupProfile().'
                </div>';
    }
    
    public function changePassword() {
        if(isset($_POST['changepass'])){
            //Form was submitted, lets do some checks...            
            $validation = new Validation();
            $validatepw = $validation->validatePassword($_POST['newpw'], $_POST['confirmpw']);
            if(!$validatepw['status']){
                $error = '<b>'.$this->_language->_accountdetails["error"].'</b> '.$this->_language->_accountdetails[$validatepw['error']].'';
            }
            
            
            $userModule = new User();
            $user = $userModule->userDetails();
            $pwEntered = hash('sha256', CONF_SECURITYSALT.$_POST['oldpw']);
            
            if($pwEntered !== $user['password']){
                $error = '<b>'.$this->_language->_accountdetails["error"].'</b> '.$this->_language->_accountdetails["old-pw-wrong"].'';
                
            }else{
                if(!isset($error)){
                    //The existing password Entered was correct, now lets change it to the new one.
                    $newPwHash = hash('sha256', CONF_SECURITYSALT.$_POST['newpw']);
                    
                    $graphModule = new Graph();
        
                    //Update the n.lastupdate to time().
                    $updateDB['query'] = "START n=node($_SESSION[uid]) SET n.password='".$newPwHash."' RETURN n;";
                    $update = $graphModule->neo4japi('cypher', 'JSONPOST', $updateDB);
                    
                    return $this->_language->_accountdetails["pw-changed"];
                }
            }
        }
        
        if(!isset($error)){
            $error = '';
        }
        
        return ''.$error.'
                <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="changepass" value="true">
                    <h2>'.$this->_language->_accountdetails["changepw"].'</h2>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="oldpw">'.$this->_language->_accountdetails["oldpw"].'</label>
                            <input type="password" class="form-control col-md-6" id="oldpw" name="oldpw" value="" placeholder="'.$this->_language->_accountdetails["oldpw"].'">
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="newpw">'.$this->_language->_accountdetails["newpw"].'</label>
                            <input type="password" class="form-control col-md-6" id="newpw" name="newpw" value="" placeholder="'.$this->_language->_accountdetails["newpw"].'">
                        </div>
                        <div class="col-md-6">
                            <label for="confirmpw">'.$this->_language->_accountdetails["confirmpw"].'</label>
                            <input type="password" class="form-control col-md-6" id="confirmpw" name="confirmpw" value="" placeholder="'.$this->_language->_accountdetails["confirmpw"].'">
                        </div>
                    </div>
                    <input type="submit" name="changepw" value="'.$this->_language->_accountdetails["changepw"].'">
                </form>';
    }
    
    public function setupProfile()
    {
        //Initiate an instance of Validation()
        $validationModule = new Validation();
        
        if(isset($_POST['setprofile'])){
            //Check the users date of birth is valid.
            $dobCheck = $validationModule->validateDateOfBirth($_POST['dob_day'], $_POST['dob_month'], $_POST['dob_year']);
            if(!$dobCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$dobCheck['error']].'';
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
            
            $emailCheck = $validationModule->validateEmailAddress($_POST['email'], $_POST['email']);
            if(!$emailCheck['status']){
                $error = '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_inputValidation[$emailCheck['error']].'';
            }
            
            //Construct the dob string.
            $dateofbirth = "$_POST[dob_day]-$_POST[dob_month]-$_POST[dob_year]";
            
            //If no errors are set we can proceed to update our database.
            if(!isset($error)){
                
                $setString = "SET n.firstname='".$_POST['firstname']."' ";
                $setString.= "SET n.lastname='".$_POST['lastname']."' ";
                $setString.= "SET n.email='".$_POST['email']."' ";
                $setString.= "SET n.birthdate='".$dateofbirth."' ";
                $setString.= "SET n.gender='".$_POST['gender']."' ";
                $setString.= "SET n.relationshipStatus='".$_POST['relationshipstatus']."' ";
                $setString.= "SET n.sexualPref='".$_POST['sexualpref']."' ";
                $setString.= "SET n.location='".$_POST['location']."' ";
                $setString.= "SET n.institution='".$_POST['institution']."' ";
                
                $graphModule = new Graph();
        
                $updateDB['query'] = "START n=node($_SESSION[uid]) $setString RETURN n;";
                $update = $graphModule->neo4japi('cypher', 'JSONPOST', $updateDB);
                
            
            $error = $this->_language->_accountdetails["profileupdated"];
            }
            
        }
        
        if(!isset($error)){
            $error = '';
        }
        
        $userModule = new User;
        $user = $userModule->userDetails();
        
        $dob = explode("-", $user['birthdate']);
        
        $html = ''.$error.'
                <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="setprofile" value="true">
                    <h2>'.$this->_language->_accountdetails["setup-profile"].'</h2>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="firstname">'.$this->_language->_accountdetails["firstname"].'</label>
                            <input type="text" class="form-control col-md-6" id="firstname" name="firstname" value="'.$user['firstname'].'" placeholder="'.$this->_language->_accountdetails["firstname"].'">
                        </div>
                        <div class="col-md-6">
                            <label for="lastname">'.$this->_language->_accountdetails["lastname"].'</label>
                            <input type="text" class="form-control col-md-6" id="lastname" name="lastname" value="'.$user['lastname'].'" placeholder="'.$this->_language->_accountdetails["lastname"].'">
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="email">'.$this->_language->_accountdetails["emailaddress"].'</label>
                            <input type="text" class="form-control col-md-6" id="email" name="email" value="'.$user['email'].'" placeholder="'.$this->_language->_accountdetails["emailaddress"].'">
                        </div>
                        <div class="col-md-6">
                            <label for="dob_day">'.$this->_language->_accountdetails["birthdate"].'</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" id="dob_day" name="dob_day" class="form-control" value="'.$dob[0].'" placeholder="'.$this->_language->_frontPage["day"].'">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="dob_month" name="dob_month" class="form-control" value="'.$dob[1].'" placeholder="'.$this->_language->_frontPage["month"].'">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="dob_year" name="dob_year" class="form-control" value="'.$dob[2].'" placeholder="'.$this->_language->_frontPage["year"].'">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="gender">'.$this->_language->_accountdetails["gender"].'</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="radio">
                                      <label>
                                          <input ';
                                          
        if($user['gender'] === "male"){
            $html.= " checked ";
        }                                     
        $html.= 'type="radio" name="gender" id="gender_male" value="male">
                                          '.$this->_language->_frontPage["male"].'
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="radio">
                                        <label>
                                            <input ';
        if($user['gender'] === "female"){
            $html.= " checked ";
        }
        $html.= 'type="radio" name="gender" id="gender_female" value="female">
                                            '.$this->_language->_frontPage["female"].'
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="relationshipstatus">'.$this->_language->_accountdetails["relationshipstatus"].'</label>
                            <input type="text" class="form-control col-md-6" id="relationshipstatus" name="relationshipstatus" value="'.$user['relationshipStatus'].'" placeholder="'.$this->_language->_accountdetails["relationshipstatus"].'">
                        </div>
                        <div class="col-md-6">
                            <label for="sexualpref">'.$this->_language->_accountdetails["sexualpref"].'</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="radio">
                                      <label>
                                          <input ';
                                          
        if($user['sexualPref'] === "male"){
            $html.= " checked ";
        }                                     
        $html.= 'type="radio" name="sexualpref" id="pref_male" value="male">
                                          '.$this->_language->_frontPage["male"].'
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="radio">
                                        <label>
                                            <input ';
        if($user['sexualPref'] === "female"){
            $html.= " checked ";
        }
        $html.= 'type="radio" name="sexualpref" id="pref_female" value="female">
                                            '.$this->_language->_frontPage["female"].'
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                           
                        <div class="col-md-6">
                            <label for="location">'.$this->_language->_accountdetails["location"].'</label>
                            <input type="text" class="form-control col-md-6" id="location" name="location" value="'.$user['location'].'" placeholder="'.$this->_language->_accountdetails["location"].'">
                        </div>
                        <div class="col-md-6">
                            <label for="institution">'.$this->_language->_accountdetails["institution"].'</label>
                            <input type="text" class="form-control col-md-6" id="institution" name="institution" value="'.$user['institution'].'" placeholder="'.$this->_language->_accountdetails["institution"].'">
                        </div>
                    </div>
                    <input type="submit" name="changepw" value="'.$this->_language->_accountdetails["save-profile"].'">
                </form>';
                
        return $html;
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    
 }
 
 ?>