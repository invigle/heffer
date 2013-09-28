<?php
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language,
    Invigle\Graph,
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
            if(isset($_GET['a'])){
                if($_GET['a'] === "new"){
                    if(isset($_POST['saveNewEvent'])){
                        $eventsModule = new Event();
                        $newEventId = $eventsModule->addEvent($_POST);
                        echo '<script>window.location.href = "event.php?eventid='.$newEventId.'";</script>';
                    }else{
                        echo $this->renderNewEventForm();
                    }
                }
            }else{
                echo $this->renderEventPage($_GET['eventid']);
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
                                    <input type="radio" name="paymentType" value="onetime" id="oneTime"> <label for="oneTime">'.$this->_language->_events['one-time'].'</label>
                                    <input type="radio" name="paymentType" value="recurring" id="feeRecurring"> <label for="feeRecurring">'.$this->_language->_events['recurring'].'</label>
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

    public function checkAttendeeStatus($userId, $eventId)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$userId.'/relationships/out/attendeeOf', 'GET');
        foreach ($rels as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $eventId)
			{
				return true;
				break;
			}
		}
		return false;
    }
    
    public function checkInviteStatus($userId, $eventId)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$userId.'/relationships/out/invitedTo', 'GET');
        foreach ($rels as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $eventId)
			{
				return true;
				break;
			}
		}
		return false;
    }
    
    public function countInvitesSent($eventId)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$eventId.'/relationships/in/invitedTo', 'GET');
    return count($rels);
    }

    public function renderEventPage($eventId)
    {
        $graphModule = new Graph();
        $userModule = new User();
        $eventModule = new Event();
        
        $eventData = $graphModule->selectNodeById($eventId);
        $event = $eventData['data'][0][0]['data'];
        
        $html = "";
        if(isset($_GET['b'])){
            if($_GET['b'] === "attend"){
                if(!$this->checkAttendeeStatus($_SESSION['uid'], $eventId)){
                    $graphModule->addConnection($_SESSION['uid'], $eventId, 'attendeeOf');
                    $html.= $this->_language->_events['attending-this-event'];
                }
            
            }elseif($_GET['b'] === "invite"){
                if(!$this->checkInviteStatus($_GET['friendId'], $eventId)) {
                    $graphModule->addConnection($_GET['friendId'], $eventId, 'invitedTo');
                    $html.= $this->_language->_events['friend-invited'];
                }
            
            }elseif($_GET['b'] === "follow"){
                if(!$eventModule->checkFollowStatus($_SESSION['uid'], $eventId)){
                    $graphModule->addConnection($_SESSION['uid'], $eventId, 'followerOf');
                    $html.= $this->_language->_events['following-event'];
                }
            }    
        }
        
        //Get the locations coordinates for plotting a map.
        $lonlat = explode(",", $event['location']);
        $lat = $lonlat[0];
        $lon = $lonlat[1];
        
        $map = '<iframe height="210" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='.$lat.','.$lon.'&hl=es;z=14&amp;output=embed"></iframe>';
        
        $cats = explode(" ", $event['categories']);
        $categories = "";
        foreach($cats as $cat){
            $categories.= "<a href='event.php?a=search&b=category&c=$cat'>#$cat</a> ";
        }
        
        if($this->_loggedin){
            if($event['privacy'] === "public"){
                if(!$this->checkAttendeeStatus($_SESSION['uid'], $eventId)){
                    $eventLinks = '[<a href="event.php?eventid='.$eventId.'&b=attend">Count me In!</a>]';
                }else{
                    $eventLinks = ' ['.$this->_language->_events['attending-this-event'].']';
                }
                if(!$eventModule->checkFollowStatus($_SESSION['uid'], $eventId)){
                    $eventLinks.= ' [<a href="event.php?eventid='.$eventId.'&b=follow">Follow</a>]';
                }else{
                    $eventLinks.= ' ['.$this->_language->_events['following-event'].']';
                }
            }else{
                $eventLinks = "Closed Event";
            }
        }else{
            $eventLinks = "";
        }
        
        $attendees = "";
        $attendeeEdges = $graphModule->neo4japi('node/'.$eventId.'/relationships/in/attendeeOf', 'GET');
        foreach($attendeeEdges as $att){
            $atstart = explode("/", $att['start']);
            $userId = end($atstart);
            $user = $userModule->userDetailsById($userId);
            $attendees.= "<a href='user.php?username=$user[username]'>$user[firstname] $user[lastname]</a><br />";
        }
        
        $friendsList = "";
        $friends = $userModule->getFriendsList($_SESSION['uid']);
        foreach($friends as $friend){
            if(!$this->checkAttendeeStatus($friend['userid'], $eventId)){
                $friendsList.= '<a href="user.php?username='.$friend['username'].'">'.$friend['firstname'].' '.$friend['lastname'].'</a> [';
                if(!$this->checkInviteStatus($friend['userid'], $eventId)){
                    $friendsList.= '<a href="event.php?eventid='.$eventId.'&b=invite&friendId='.$friend['userid'].'">'.$this->_language->_events['invite'].'</a>';
                }else{
                    $friendsList.= $this->_language->_events['invited'];
                }
                $friendsList.= ']<br />';
            }
        }
        
        $html.= '<div class="row">
                    <div class="col-md-9">
                     <div class="row">
                        <div class="col-md-8">
                            <h2>'.$event['name'].' ('.$event['date'].')</h2>
                            <p><b>'.$this->_language->_events['attend-options'].':</b> '.$eventLinks.'</p>
                        </div>
                        <div class="col-md-2">
                            <label>'.$this->_language->_events['invited'].'</label><br />
                            '.$this->countInvitesSent($eventId).'
                        </div>
                        <div class="col-md-2">
                            <label>'.$this->_language->_events['attending'].'</label><br />
                            '.count($attendeeEdges).'
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-8">
                            '.$event['description'].'   
                        </div>
                        <div class="col-md-4">
                            '.$map.'
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-md-8">
                     
                     '.$this->_language->_events['organised-by'].' ';
                 
        if($event['ownerType'] === "user"){
            $user = $userModule->userDetailsById($event['OwnerID']);
            $html.= '<a href="user.php?username='.$user['username'].'">'.$user['firstname'].' '.$user['lastname'].'</a>';
        }
        
        $html.= ' on '.date(CONF_DATEFORMAT, $event['timestamp']).'.
                    </div>
                    <div class="col-md-4">
                        '.$categories.'
                    </div>
                </div>
               </div>
               <div class="col-md-3">
                    <b>'.$this->_language->_events['attendees'].'</b><br />
                    '.$attendees.'
                    <hr>
                    <b>'.$this->_language->_events['invite-people'].'</b><br />
                    '.$friendsList.'
               </div>
            </div>';
        
    return $html;
    }
    
    
 }
?>