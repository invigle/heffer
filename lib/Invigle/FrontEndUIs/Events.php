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
                if(isset($_POST['saveNewEvent'])){
                    $eventsModule = new Event();
                    $add = $eventsModule->addEvent($_POST);
                    print '<pre>';
                    print_r($add);
                    print '</pre>';
                }else{
                    echo $this->renderNewEventForm();
                }
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
        return '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?a=new">
                    <input type="hidden" name="saveNewEvent" value="true">
                    <input type="hidden" name="createEventAs" value="user"> <!-- This should be changed to show the option of a User or Event etc. -->
                    <h2>'.$this->_language->_events['add-new-event'].'</h2>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <label for="name">'.$this->_language->_events['name'].'</label>
                            <input type="text" name="name" value="" placeholder="'.$this->_language->_events['name-placeholder'].'" class="form-control" id="name">
                            <br />
                            <label for="description">'.$this->_language->_events['description'].'</label>
                            <textarea name="description" id="description" class="form-control" placeholder="'.$this->_language->_events['desc-placeholder'].'"></textarea>
                            <br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>'.$this->_language->_events['fee-required'].'</label><br />
                                    <input type="radio" name="isPaid" value="1" id="isPaid"> <label for="isPaid">'.$this->_language->_events['yes'].'</label>
                                    <input type="radio" name="isPaid" value="0" id="isNotPaid"> <label for="isNotPaid">'.$this->_language->_events['no'].'</label>
                                </div>
                                <div class="col-md-6">
                                    <label>'.$this->_language->_events['fee-type'].'</label><br />
                                    <input type="radio" name="paymentType" value="1" id="oneTime"> <label for="oneTime">'.$this->_language->_events['one-time'].'</label>
                                    <input type="radio" name="paymentType" value="0" id="feeRecurring"> <label for="feeRecurring">'.$this->_language->_events['recurring'].'</label>
                                </div>
                            </div>
                            <label for="location">'.$this->_language->_events['location'].'</label>
                            <input type="text" name="location" value="" placeholder="'.$this->_language->_events['location-placeholder'].'" class="form-control" id="location">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="category">'.$this->_language->_events['category'].'</label>
                            <input type="text" name="categories" value="" placeholder="'.$this->_language->_events['category-placeholder'].'" class="form-control" id="category">
                            <br />
                            <label for="type">'.$this->_language->_events['type'].'</label>
                            <select id="type" name="type" class="form-control">
                                <option value="sport">'.$this->_language->_events['sport'].'</option>
                                <option value="religion">'.$this->_language->_events['religion'].'</option>
                                <option value="business">'.$this->_language->_events['business'].'</option>
                                <option value="party">'.$this->_language->_events['party'].'</option>
                            </select>
                            <br />
                            <label for="date">'.$this->_language->_events['date'].'</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="date_day" class="form-control" value="" placeholder="DD">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="date_month" class="form-control" value="" placeholder="MM">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="date_year" class="form-control" value="" placeholder="YYYY">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>'.$this->_language->_events['privacy'].'</label><br />
                                    <input type="radio" name="privacy" value="public" id="isPublic"> <label for="isPublic">'.$this->_language->_events['public'].'</label>
                                    <input type="radio" name="privacy" value="private" id="isPrivate"> <label for="isPrivate">'.$this->_language->_events['private'].'</label>    
                                </div>
                                <div class="col-md-6">
                                    <label for="timeline">'.$this->_language->_events['show-on-timeline'].'</label><br />
                                    <input type="checkbox" name="timeline" value="yes" id="timeline">    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <br />
                    <input type="submit" name="saveEvent" value="'.$this->_language->_events['save_button'].'">
                    
                </form>';
    }
    
    
    
 }
?>