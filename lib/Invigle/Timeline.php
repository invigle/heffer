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
            'addedEvent',
        );
        
        $pageActions = array(
            'addedPage',
        );
        
        $groupActions = array(
            'addedGroup',
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
		$timelineEdges = $this->_graphModule->neo4japi('node/'.$userId.'/relationships/out/timeline', 'GET');
        foreach($timelineEdges as $edge){
            $an = explode("/", $edge['end']);
            $actionNodeId = end($an);
            
            $actionNodeRaw = $this->_graphModule->neo4japi('node/'.$actionNodeId.'/', 'GET');
            $actionNode = $actionNodeRaw['data'];        
            
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
                    'actuibType'=>$actionNode['actionType'],
                    'statusData'=>$actionNode['statusData'],
                );
            }
        }
        
        $user = $this->_userModule->userDetailsById($userId);
    
        /*********************************************************
         *  STYLE THE ARRAY CONSTRUCTED ABOVE.
         *********************************************************/
         //Temporary Vars because language isnt working.
         $this->_language->_timeline['started-following'] = "started following";
         $this->_language->_timeline['is-friends-with'] = "is now friends with";
         $this->_language->_timeline['updated-status'] = "updated their status";

         //Sort $rtn by $rtn[]['timestamp'];
         usort($rtn, function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
         });
         $rtn = array_reverse($rtn, true);

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
                $html.= ''.$user['firstname'].' '.$this->_language->_timeline['updated-status'].'<br />'.$act['statusData'].'';
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