<?php
namespace Invigle;
 
class Timeline{
    
    public function __construct()
    {
        $this->_graphModule = new Graph();
        $this->_userModule = new User();
    }

    /**
     * Action Node actionType sections
     * @param $action
     * @return string / user, page, event, group, error
     */
    public function getActionType($action){
        //List of all different types of 'actionType' edges for comparison.
        $userActions = array(
            'followerOf',
            'friendOf',
        );
        
        $eventActions = array(
            'newEvent',
        );
        
        $pageActions = array(
            'addedPage',
        );
        
        $groupActions = array(
            'newGroup',
        );
        
        $statusActions = array(
            'newStatus',
        );
        
        //Now lets see which one the action falls into.
        if(in_array($action, $userActions)){
            return 'user';
        }
        if(in_array($action, $eventActions)){
            return 'event';
        }
        if(in_array($action, $pageActions)){
            return 'page';
        }
        if(in_array($action, $groupActions)){
            return 'group';
        }
        if(in_array($action, $statusActions)){
            return 'status';
        }
    
    //Not found, return error for you to decide what todo with it.
    return 'error';
    }

	/**
     * Generate a users timeline
	 * 
     * @param $userId
     * @return html output
	 */
	public function createTimeline($userId) {
        $timelineEdges = $this->_graphModule->transverseNodes($userId, 'timeline', '1', '10');
        
        foreach($timelineEdges as $edgeArr){          
            $edge = $edgeArr[0];
            $actionNode = $edge['data']; 
            
            
            if($this->getActionType($actionNode['actionType']) === "user"){
                //We have determined that this is a user-related action, so grab the destination users details and return a nice array.
                $userNode = $this->_userModule->userDetailsById($actionNode['uid']);
                
                $rtn[] = array(
                    'tlType'=>"user",
                    'timestamp'=>$actionNode['timestamp'],
                    'actionType'=>$actionNode['actionType'],
                    'userid'=>$actionNode['uid'],
                    'firstname'=>$userNode['firstname'],
                    'lastname'=>$userNode['lastname'],
                    'username'=>$userNode['username'],
                );
            
            }elseif($this->getActionType($actionNode['actionType']) === "status"){
                //This is a STATUS update that we are showing... Lets do it...
                $rtn[] = array(
                    'tlType'=>'status',
                    'timestamp'=>$actionNode['timestamp'],
                    'actionType'=>$actionNode['actionType'],
                    'statusData'=>$actionNode['statusData'],
                );
            
            }elseif($this->getActionType($actionNode['actionType']) === "event"){
                //This is EVENT related...
                //Get the Event Node
                $eventNode = $this->_graphModule->selectNodeById($actionNode['uid']);
                $eventData = $eventNode['data'][0][0]['data'];
                
                $event = array(
                    'tlType' =>'event',
                    'timestamp' => $eventData['timestamp'],
                    'actionType' => 'addEvent',
                    'eventid' => $actionNode['uid'],
                );
                
                
                $rtn[] = array_merge($event, $eventData);
                
            }elseif($this->getActionType($actionNode['actionType']) === "group"){
                //This is EVENT related...
                //Get the Event Node
                $groupNode = $this->_graphModule->selectNodeById($actionNode['uid']);
                $groupData = $groupNode['data'][0][0]['data'];
                
                $group = array(
                    'tlType' =>'group',
                    'timestamp' => $groupData['timestamp'],
                    'actionType' => 'addEvent',
                    'groupid' => $actionNode['uid'],
                );
                
                
                $rtn[] = array_merge($group, $groupData);
            }
        }

        $user = $this->_userModule->userDetailsById($userId);
    
        /*********************************************************
         *  STYLE THE ARRAY CONSTRUCTED ABOVE.
         *********************************************************/
         //Temporary Vars because language isnt working.
         $this->_language->_timeline['started-following'] = "started following";
         $this->_language->_timeline['is-friends-with'] = "is now friends with";
         $this->_language->_timeline['updated-his-status'] = "updated his status";
         $this->_language->_timeline['updated-her-status'] = "updated her status";
         $this->_language->_timeline['started-event'] = "Started Event: ";
         $this->_language->_timeline['started-group'] = "Started Group: ";

         $html = '';
         foreach($rtn as $act){
         
         $html.= '<i>'.date(CONF_DATEFORMAT, $act['timestamp']).'</i><br />';
            if($act['tlType'] === "user"){
                if($act['actionType'] === "followerOf"){
                    $html.= ''.$user['firstname'].' '.$this->_language->_timeline['started-following'].' <a href="user.php?username='.$act['username'].'">'.$act['firstname'].' '.$act['lastname'].'</a>';
                }elseif($act['actionType'] === "friendOf"){
                    $html.= ''.$user['firstname'].' '.$this->_language->_timeline['is-friends-with'].' <a href="user.php?username='.$act['username'].'">'.$act['firstname'].' '.$act['lastname'].'</a>';
                }
            
            }elseif($act['tlType'] === "status"){
                if($user['gender'] === "male"){
                    $html.= ''.$user['firstname'].' '.$this->_language->_timeline['updated-his-status'].'<br />'.$act['statusData'].'';
                }else{
                    $html.= ''.$user['firstname'].' '.$this->_language->_timeline['updated-her-status'].'<br />'.$act['statusData'].'';
                }
            
            }elseif($act['tlType'] === "event"){   
                $html.= ''.$user['firstname'].' '.$this->_language->_timeline['started-event'].' <a href="event.php?eventid='.$act['eventid'].'">'.$act['name'].'</a>';
            
            }elseif($act['tlType'] === "group"){   
                $html.= ''.$user['firstname'].' '.$this->_language->_timeline['started-group'].' <a href="group.php?groupid='.$act['groupid'].'">'.$act['name'].'</a>';
            
            }
         $html.= '<hr>';
         }
  
    return $html;
	}

	/**
	 * @access public
	 */
	public function updateTimeline() {
		// Not yet implemented
	}
}
?>