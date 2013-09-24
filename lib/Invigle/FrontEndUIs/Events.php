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
 
class Events extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */    
    public function __construct(Language $theLanguage)
    {
        $this->_language = $theLanguage;
        parent::__construct();
        $this->_pageTitle = $this->_language->_events['pageTitle'];
        
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
            if($_GET['a'] === "new"){
                echo $this->renderNewEventForm();
            }else{
                echo $this->renderEventPage($_GET['eventId']);
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
    
    public function renderEventPage()
    {
        //To be added
    }
    
    public function renderNewEventForm()
    {
        return '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                    <input type="hidden" name="saveNewEvent" value="">
                    <b>'.$this->_language->_events['add-new-event'].'</b><br />
                    
                    <div class="row">
                        <div class="col-md-8">
                            <label for="name">[NAME]</label>
                            <input type="text" name="name" value="" placeholder="[NAME-PLACEHOLDER]" class="form-control" id="name">
                            <br />
                            <label for="description">[DESCRIPTION]</label>
                            <textarea name="description" id="description" class="form-control" placeholder="[DESC-PLACEHOLDER]"></textarea>
                            <br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>[FEE-REQUIRED]</label><br />
                                    <input type="radio" name="isPaid" value="1" id="isPaid"> <label for="isPaid">[YES]</a>
                                    <input type="radio" name="isPaid" value="0" id="isNotPaid"> <label for="isNotPaid">[NO]</a>
                                </div>
                                <div class="col-md-6">
                                    <label>[FEE-TYPE]</label><br />
                                    <input type="radio" name="paymentType" value="1" id="oneTime"> <label for="oneTime">[ONE-TIME]</a>
                                    <input type="radio" name="paymentType" value="0" id="feeRecurring"> <label for="feeRecurring">[RECURRING]</a>
                                </div>
                            </div>
                            <label for="location">[LOCATION]</label>
                            <input type="text" name="location" value="" placeholder="[LOCATION-PLACEHOLDER]" class="form-control" id="location">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="category">[CATEGORY]</label>
                            <input type="text" name="category" value="" placeholder="[CATEGORY-PLACEHOLDER]" class="form-control" id="category">
                            <br />
                            <label for="type">[TYPE]</label>
                            <select id="type" name="type" class="form-control">
                                <option value="sport">[SPORT]</option>
                                <option value="religion">[RELIGION]</option>
                                <option value="business">[BUSINESS]</option>
                                <option value="party">[PARTY]</option>
                            </select>
                            <br />
                            <label for="date">[DATE]</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="dob_day" class="form-control" value="" placeholder="DD">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="dob_month" class="form-control" value="" placeholder="MM">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="dob_year" class="form-control" value="" placeholder="YYYY">
                                </div>
                            </div>
                            <br />
                            <label>[PRIVACY]</label><br />
                            <input type="radio" name="privacy" value="1" id="isPublic"> <label for="isPublic">[PUBLIC]</a>
                            <input type="radio" name="privacy" value="0" id="isPrivate"> <label for="isPrivate">[PRIVATE]</a>
                        </div>
                    </div>
                    
                </form>';
    }
    
    
    
 }
?>