<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\User,
    Invigle\Graph;

 
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
                </div>';
    }
    
    public function changePassword() {
        if(isset($_POST['changepass'])){
            //Form was submitted, lets do some checks...
            if($_POST['newpw'] !== $_POST['confirmpw']){
                $error = '<b>'.$this->_language->_accountdetails["error"].'</b> '.$this->_language->_accountdetails["pw-dont-match"].'';
            }
            
            if(strlen($_POST['newpw']) < "6"){
                $error = '<b>'.$this->_language->_accountdetails["error"].'</b> '.$this->_language->_accountdetails["pw-too-short"].'';
            }
            if(is_numeric($_POST['newpw'])){
                $error = '<b>'.$this->_language->_accountdetails["error"].'</b> '.$this->_language->_accountdetails["pw-is-numeric"].'';
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
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    
 }
 
 ?>