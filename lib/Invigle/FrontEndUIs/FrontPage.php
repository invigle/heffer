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
        echo $this->registrationForm($_POST);
        echo $this->renderJSLinks();
        echo '</body>';
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    private function registrationForm($_POST)
    {   
        if(isset($_POST['regform'])){
            $user = new User();
            
            $userArray = array(
                            'username'=>$_POST['username'],
                            'password'=>$_POST['password'],
                            'firstname'=>$_POST['firstname'],
                            'lastname'=>$_POST['lastname'],
                            'email'=>$_POST['email'],
                            'birthdate'=>"$_POST[dob_day]-$_POST[dob_month]-$_POST[dob_year]",
                            'gender'=>$_POST['gender']
                              );
            
            //Check that the Date of Birth fields are numeric values only.
            if(!is_numeric($_POST['dob_day']) || !is_numeric($_POST['dob_month']) || !is_numeric($_POST['dob_year'])){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["dob-invalid"].'';
            }
            
            //Check that the users password is more than 6 characters long.
            if(strlen($_POST['password'] <= "6")){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["pw-too-short"].'';
            }
            
            //Check that firstname and lastname are more than 2 characters long and are not numeric.
            if(strlen($_POST['firstname']) < "2"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["firstname-too-short"].'';
            }
            if(is_numeric($_POST['firstname'])){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["name-is-numeric"].'';
            }
            if(strlen($_POST['lastname']) < "2"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["lastname-too-short"].'';
            }
            if(is_numeric($_POST['lastname'])){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["name-is-numeric"].'';
            }
            
            //Attempt to add the user.
            $add = $user->addUser($userArray);
            
            if($add === "email-taken"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["email-taken"].'';
            }elseif($add === "username-taken"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["username-taken"].'';
            }elseif($add === "username-invalid"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["username-invalid"].'';
            }elseif($add === "email-invalid"){
                return '<b>'.$this->_language->_frontPage["error"].': </b>'.$this->_language->_frontPage["email-invalid"].'';
            }
            
            print '<pre>';
            print_r($add);
            print '</pre>';
        }
        
        return '<div class="container">
                    <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="regform" value="submit">
                        <h2>'.$this->_language->_frontPage["register"].'</h2>
                        <div class="row-fluid">
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="firstname" value="" placeholder="'.$this->_language->_frontPage["firstname"].'">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="lastname" value="" placeholder="'.$this->_language->_frontPage["lastname"].'">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-md-12">
                                <input type="text" class="form-control col-md-12" name="email" value="" placeholder="'.$this->_language->_frontPage["emailaddress"].'">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-md-12">
                                <input type="text" class="form-control col-md-12" name="confirmemail" value="" placeholder="'.$this->_language->_frontPage["confirmemailaddress"].'">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-6" name="username" value="" placeholder="'.$this->_language->_frontPage["username"].'">
                            </div>
                            <div class="col-md-6">
                                <input type="password" class="form-control col-md-6" name="password" value="" placeholder="'.$this->_language->_frontPage["password"].'">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-md-6">
                                <h4>'.$this->_language->_frontPage["birthdate"].'</h4>
                                <div class="row-fluid">
                                    <div class="col-md-3">
                                        <input type="text" name="dob_day" class="form-control" placeholder="'.$this->_language->_frontPage["day"].'">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="dob_month" class="form-control" placeholder="'.$this->_language->_frontPage["month"].'">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="dob_year" class="form-control" placeholder="'.$this->_language->_frontPage["year"].'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>'.$this->_language->_frontPage["gender"].'</h4>
                                <div class="row-fluid">
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
                    </form>
                </div>';
    }
    
 }
 
 ?>