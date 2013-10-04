<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\Graph,
    Invigle\User,
    Invigle\Validation,
    Invigle\Status,
    Invigle\Group;

 
/**
 * FrontPage - This renders the front home page
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class Groups extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */    
    public function __construct(Language $theLanguage)
    {
        $this->_language = $theLanguage;
        parent::__construct();
        $this->_pageTitle = $this->_language->_groups['pageTitle'];
        
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
        echo '<div class="container">';
            if(isset($_GET['a'])){
                if($_GET['a'] === "new"){
                    if(isset($_POST['saveNewGroup'])){
                        $groupsModule = new Group();
                        $groupId = $groupsModule->addGroup($_POST);
                        echo '<script>window.location.href = "group.php?groupid='.$groupId.'";</script>';
                    }else{
                        echo $this->renderNewGroupForm();
                    }
                }
            }else{
                //echo $this->renderEventPage($_GET['eventid']);
                echo "Not Built $_GET[groupid]";
            }
        echo '</div>';
        echo $this->renderJSLinks();
        echo '</body>';
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    public function renderNewGroupForm()
    {
        return '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?a=new">
                    <input type="hidden" name="saveNewGroup" value="true">
                    <h2>'.$this->_language->_groups['add-new-group'].'</h2>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <label for="name">'.$this->_language->_groups['name'].'</label>
                            <input type="text" name="name" value="" placeholder="'.$this->_language->_groups['name-placeholder'].'" class="form-control" id="name">
                            <br />
                            <label for="slogan">'.$this->_language->_groups['slogan'].'</label>
                            <input type="text" name="slogan" value="" placeholder="'.$this->_language->_groups['slogan-placeholder'].'" class="form-control" id="slogan">
                            <br />
                            <label for="website">'.$this->_language->_groups['website'].'</label>
                            <input type="text" name="website" value="" placeholder="'.$this->_language->_groups['website-placeholder'].'" class="form-control" id="website">
                            <br />
                            <label for="description">'.$this->_language->_groups['description'].'</label>
                            <textarea name="description" id="description" class="form-control" placeholder="'.$this->_language->_groups['desc-placeholder'].'"></textarea>
                            <br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>'.$this->_language->_groups['fee-required'].'</label><br />
                                    <input type="radio" name="isPaid" value="1" id="isPaid"> <label for="isPaid">'.$this->_language->_groups['yes'].'</label>
                                    <input type="radio" name="isPaid" value="0" id="isNotPaid"> <label for="isNotPaid">'.$this->_language->_groups['no'].'</label>
                                </div>
                                <div class="col-md-6">
                                    <label>'.$this->_language->_groups['fee-type'].'</label><br />
                                    <input type="radio" name="paymentType" value="onetime" id="oneTime"> <label for="oneTime">'.$this->_language->_groups['one-time'].'</label>
                                    <input type="radio" name="paymentType" value="recurring" id="feeRecurring"> <label for="feeRecurring">'.$this->_language->_groups['recurring'].'</label>
                                </div>
                            </div>
                            <label for="location">'.$this->_language->_groups['location'].'</label>
                            <input type="text" name="location" value="" placeholder="'.$this->_language->_groups['location-placeholder'].'" class="form-control" id="location">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="category">'.$this->_language->_groups['category'].'</label>
                            <input type="text" name="categories" value="" placeholder="'.$this->_language->_groups['category-placeholder'].'" class="form-control" id="category">
                            <br />
                            <label for="type">'.$this->_language->_groups['type'].'</label>
                            <select id="type" name="type" class="form-control">
                                <option value="sport">'.$this->_language->_groups['sport'].'</option>
                                <option value="religion">'.$this->_language->_groups['religion'].'</option>
                                <option value="business">'.$this->_language->_groups['business'].'</option>
                                <option value="party">'.$this->_language->_groups['party'].'</option>
                            </select>
                            <br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>'.$this->_language->_groups['privacy'].'</label><br />
                                    <input type="radio" name="privacy" value="public" id="isPublic"> <label for="isPublic">'.$this->_language->_groups['public'].'</label>
                                    <input type="radio" name="privacy" value="private" id="isPrivate"> <label for="isPrivate">'.$this->_language->_groups['private'].'</label>    
                                </div>
                                <div class="col-md-6">
                                    <label for="timeline">'.$this->_language->_groups['show-on-timeline'].'</label><br />
                                    <input type="checkbox" name="timeline" value="yes" id="timeline">    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <br />
                    <input type="submit" name="saveEvent" value="'.$this->_language->_groups['save_button'].'">
                    
                </form>';
    }
    
    
 }
?>