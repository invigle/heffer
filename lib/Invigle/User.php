<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class User
{
	/**
	 * @AttributeType string
	 * This holds the first name of the user
	 */
	private $_firstName;
	/**
	 * @AttributeType string
	 * This holds the last name of the user
	 */
	private $_lastName;
	/**
	 * @AttributeType string
	 * This holds the location of the user
	 */
	private $_location;
	/**
	 * @AttributeType string
	 * This holds the email of the user
	 */
	private $_email;
	private $_password;
	private $_birthday;
	private $_institution;
	private $_relationshipStatus;
	private $_gender;
	private $_sexualPref;
	private $_uID;
	private $_profilePicID;
	private $_followerCount;
	private $_friendCount;
	private $_url;

	/**
	 * This method will check the graph database to ensure a username is unique.
	 * @param username
	 * @return boolean (true if Available, false if Taken)
	 */
	public function validateUsername($username)
	{
		$graph = new Graph();
		$check['query'] = "MATCH n:User WHERE n.username = \"$username\" RETURN count(*);";
		$api = $graph->neo4japi('cypher', 'JSONPOST', $check);

		if ($api['data'][0][0] >= "1")
		{
			return false;
		} else
		{
			return true;
		}
	}

	/**
	 * This method will check the graph database to ensure a email address is unique and valid
	 * @param email
	 * @return boolean (true if Available, false if Taken)
	 */
	public function validateEmailAddress($email)
	{
		$graph = new Graph();

		$check['query'] = "MATCH n:User WHERE n.email = \"$email\" RETURN count(*);";
		$api = $graph->neo4japi('cypher', 'JSONPOST', $check);

		if ($api['data'][0][0] >= "1")
		{
			return false;
		} else
		{
			return true;
		}
	}

	/**
	 * This method takes as input an array with all the information of a user and adds this user to the GD as a 'user node'.
	 * @access public
	 * @param aUserArray
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function addUser($aUserArray)
	{
		if (!$this->validateUsername($aUserArray['username']))
		{
			return 'username-taken';
		}

		if (!$this->validateEmailAddress($aUserArray['email']))
		{
			return 'email-taken';
		}

		//Create the new user account in neo4j
		$graph = new Graph();

		$queryString = "";
		foreach ($aUserArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$user['query'] = "CREATE (n:User {" . $queryString . "}) RETURN n;";

		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $user);

		//Email the new User their login credentials?

		//Login the new User and forward them to a profile page.

		//return the New User ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$userId = end($bit);

		return $userId;
	}

	/**
	 * This method takes input as an array from $_POST and logs in a user.
	 */
	public function loginUser(array $userInput)
	{
		$graph = new Graph();

		$run['query'] = "MATCH n:User WHERE n.email = '$userInput[email]' RETURN n;";
		$login = $graph->neo4japi('cypher', 'JSONPOST', $run);

		if (isset($login['data'][0][0]['data']))
		{
			$userData = $login['data'][0][0]['data'];

			//Extract the users ID# from the database
			$bit = explode("/", $login['data'][0][0]['self']);
			$usersID = end($bit);

			$inputPassword = hash('sha256', CONF_SECURITYSALT . $userInput['password']);

			if ($inputPassword === $userData['password'])
			{
				/* Handle Users Session */
				//This function generates a new function to prevent screenwatch driven session hijacks.
				session_regenerate_id();

				//Set the sessions based on session_id();
				$_SESSION['uid'] = $usersID;
				$_SESSION['sid'] = session_ID();

				if (!isset($userInput['rememberme']))
				{
					$userInput['rememberme'] = "no";
				}

				//Store the users logged in status in Neo4j.
				$updateDB['query'] = "MATCH n:User WHERE n.email = '$userInput[email]' SET n.sessionid='$_SESSION[sid]' SET n.ipaddress='$_SERVER[REMOTE_ADDR]' SET n.lastAction='" .
					time() . "' SET n.rememberme='" . $userInput['rememberme'] . "' RETURN n;";
				$update = $graph->neo4japi('cypher', 'JSONPOST', $updateDB);

				return true;
			} else
			{
				return false;
			}


		} else
		{
			return false;
		}


		return false;
	}

	/**
	 * This method checks the currently set $_SESSION data against the neo4j database and returns a boolean for true or false responses.
	 */
	public function validateSession()
	{
		$graph = new Graph();

		$val['query'] = "MATCH n:User WHERE n.sessionid='$_SESSION[sid]' RETURN n;";
		$api = $graph->neo4japi('cypher', 'JSONPOST', $val);

		if (isset($api['data'][0][0]['data']))
		{
			$userInfo = $api['data'][0][0]['data'];
			if ($userInfo['ipaddress'] !== $_SERVER['REMOTE_ADDR'])
			{
				//IP Does not match session, Kick this fool out!
				$_SESSION['sid'] = "";
				$_SESSION['uid'] = "";
				session_destroy();
				return false;
			} else
			{
				//This guys for true, let em' stay.
				return true;
			}

		} else
		{
			return false;
		}
	}

	/**
	 * This method will collect users data based on the logged in session id.
	 */
	public function userDetails()
	{
		$graph = new Graph();

		$usr['query'] = "START n=node($_SESSION[uid]) RETURN n;";
		$api = $graph->neo4japi('cypher', 'JSONPOST', $usr);

		return $api['data'][0][0]['data'];
	}
    
    /**
	 * This method will collect users data based on the provided userID
     * 
     * @param $userID
     * @return array
	 */
	public function userDetailsById($uID)
	{
		$graph = new Graph();

		$usr['query'] = "START n=node($uID) RETURN n;";
		$api = $graph->neo4japi('cypher', 'JSONPOST', $usr);

		return $api['data'][0][0]['data'];
	}

	/**
	 * This method will logout the currently logged in user.
	 */
	public function userLogout()
	{
		$_SESSION['sid'] = "";
		$_SESISON['uid'] = "";
		session_destroy();
		return true;
	}

	/**
	 * This method takes as input the ID of a user and deletes the node that represent this user from the GD.
	 * @access public
	 * @param aUID
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function deleteUser($aUID)
	{
		// Not yet implemented
	}

	/**
	 * This method edits some of the properties of a user in the GD by updating the current node in the GD with information provided by the userArray which is the input to the editUser method
	 * @access public
	 * @param aUserArray
	 * @return boolean
	 * 
	 * @ReturnType boolean
	 */
	public function editUser($aUserArray)
	{
		// Not yet implemented
	}

	/**
	 * This method will update a Users 'lastaction' field with the current UTC Timestamp.
	 * 
	 * @access public
	 * @param userID
	 * @return none
	 */
	public function updateUserTimestamp($userID)
	{
		$graph = new Graph();

		//Update the n.lastupdate to time().
		$updateDB['query'] = "START n=node($userID) SET n.lastupdate='" . time() .
			"' RETURN n;";
		$update = $graph->neo4japi('cypher', 'JSONPOST', $updateDB);
	}


	/*********************************************************
     * USER-USER RELATED
     *********************************************************/
    
    /**
     * Check if somebody is already following somebody
     * 
     * @params $follower, $followee
     * @return boolean
     */
    public function checkFollowStatus($follower, $followee)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$follower.'/relationships/out/followerOf', 'GET');
        foreach($rels as $rel){
            $en = explode("/", $rel['end']);
            if(end($en) === $followee){
                return true;
                break;
            }
        }
        return false;
    }
    
    /**
     * List all followers of userid
     * 
     * @param $username
     * @return array
     */
    public function userFollowersList($uID)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$uID.'/relationships/in/followerOf', 'GET');
                
        $i = 0;
        foreach($rels as $follower){
            $st = explode('/', $follower['start']);
            $user = $this->userDetailsById(end($st));
            
            $rtn[$i]['userid'] = end($st);
            $rtn[$i]['username'] = $user['username'];
            $rtn[$i]['firstname'] = $user['firstname'];
            $rtn[$i]['lastname'] = $user['lastname'];
        $i++;
        }
    
        if(isset($rtn)){
            return $rtn;
        }
    }
    
    /**
     * List all users being followed by userid
     * 
     * @param $username
     * @return array
     */
    public function userFollowingList($uID)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$uID.'/relationships/out/followerOf', 'GET');
                
        $i = 0;
        foreach($rels as $follower){
            $st = explode('/', $follower['end']);
            $user = $this->userDetailsById(end($st));
            
            $rtn[$i]['userid'] = end($st);
            $rtn[$i]['username'] = $user['username'];
            $rtn[$i]['firstname'] = $user['firstname'];
            $rtn[$i]['lastname'] = $user['lastname'];
        $i++;
        }
    
        if(isset($rtn)){
            return $rtn;
        }
    }
    
	/**
	 * Follow Somebody
	 * 
	 * Requires Variables:
	 * UID (User ID Follwer)
	 * UID2 (User ID of the Followee)
	 */
	public function followUser($follower, $followee)
	{
		$graph = new Graph();

		//Add the relationship between follower and followee.
		$api = $graph->addConnection($follower, $followee, 'followerOf');

		//Add an Action node for the action and return the node id.
		$user['query'] = "CREATE (n:Action { actionType : \"followerOf\", timestamp : \"" .
			time() . "\", uid : \"$followee\" }) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $user);

		//Get NodeID of Action
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$actionId = end($bit);

		//Add a relationship from follower to action node.
		$action = $graph->addConnection($follower, $actionId, 'timeline');

		//Update the Users last action timestamp.
		$this->updateUserTimestamp($follower);

		//Update Number of Followers
		$this->increaseFollowersCount($followee);
	}

	public function getNumberOfFollowers($uID)
	{
        $graph = new Graph();
		$api['query'] = "START n=node($uID) RETURN n;";
        $apiCall = $graph->neo4japi('cypher', 'JSONPOST', $api);
        $user = $apiCall['data'][0][0]['data'];
        
    return $user['followerCount'];
	}

	public function setNumberOfFollowers($uID, $count)
	{
		$graph = new Graph();
        $this->_followerCount = $count;
        $api['query'] = "START n=node($uID) SET n.followerCount='$count' RETURN n;";
        $apiCall = $graph->neo4japi('cypher', 'JSONPOST', $api);
	}

	public function increaseFollowersCount($uID)
	{
		$followers = $this->getNumberOfFollowers($uID) + 1;
		$this->setNumberOfFollowers($uID, $followers);
	}

	public function unfollowUser($follower, $followee)
	{
		// to be implemented
	}

    /**
     * Check if somebody is already friends with somebody
     * 
     * @params $follower, $followee
     * @return boolean
     */
    public function checkFriendStatus($follower, $followee)
    {
        $graphModule = new Graph();
        $friends = $graphModule->neo4japi('node/'.$follower.'/relationships/all/friendOf', 'GET');
        foreach($friends as $rel){
            $en = explode("/", $rel['end']);
            if(end($en) === $followee){
                return true;
                break;
            }
        }
        
        $frReqs = $graphModule->neo4japi('node/'.$follower.'/relationships/all/friendRequest', 'GET');
        foreach($frReqs as $rel){
            $en = explode("/", $rel['end']);
            if(end($en) === $followee){
                return true;
                break;
            }
        }
        
        return false;
    }

	/**
	 * This method takes as inputs a two users' IDs and adds a FRIEND_OF edge to neo4j.
	 * @access public
	 * @param uID = Source, uID2 = Destination
	 * @return boolean
	 */
	public function addFriend($uID, $uID2)
	{
		$graphModule = new Graph();
        $action = $graphModule->addConnection($uID, $uID2, 'friendRequest');
	}
    
    /**
     * This method will accept a friends request.
     * @params userid, friendid, friendOf.
     * @return none
     */
    public function acceptFriend($uID1, $uID2)
    {
        $graphModule = new Graph();
        $graphModule->deleteConnection($uID1, $uID2, 'friendRequest');
        $graphModule->addConnection($uID1, $uID2, 'friendOf');
        $graphModule->addConnection($uID2, $uID1, 'friendOf');
        
        //Add an Action node for the action and return the node id.
        //Add the actionNode from Requester to Destination
		$user['query'] = "CREATE (n:Action { actionType : \"friendOf\", timestamp : \"" .
			time() . "\", uid : \"$uID2\" }) RETURN n;";
		$apiCall = $graphModule->neo4japi('cypher', 'JSONPOST', $user);
        
        //Now add it the other way around.
        $dest['query'] = "CREATE (n:Action { actionType : \"friendOf\", timestamp : \"" .
			time() . "\", uid : \"$uID1\" }) RETURN n;";
        $apiCallD = $graphModule->neo4japi('cypher', 'JSONPOST', $dest);
        
		//Get NodeID of Action (User -> Friend)
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$actionId = end($bit);
        
        //Get NodeID of Action (Friend -> User)
		$bot = explode("/", $apiCallD['data'][0][0]['self']);
		$actionIdD = end($bot);

		//Add a relationship from follower to action node.
		$graphModule->addConnection($uID1, $actionId, 'timeline');
        
        //Add a relationship from friend->user to action node.
		$graphModule->addConnection($uID2, $actionIdD, 'timeline');       

		//Update the Users last action timestamp.
		$this->updateUserTimestamp($uID1);
        $this->updateUserTimestamp($uID2);
        
        //Update both users friend count.
        $this->increaseFriendsCount($uID1);
        $this->increaseFriendsCount($uID2);
        
    }
    
	public function getNumberOfFriends($uID)
	{
        $graph = new Graph();
		$api['query'] = "START n=node($uID) RETURN n;";
        $apiCall = $graph->neo4japi('cypher', 'JSONPOST', $api);
        $user = $apiCall['data'][0][0]['data'];
        
    return $user['friendCount'];
	}

	public function setNumberOfFriends($uID, $count)
	{
		$graph = new Graph();
        $this->_friendCount = $count;
        $api['query'] = "START n=node($uID) SET n.friendCount='$count' RETURN n;";
        $apiCall = $graph->neo4japi('cypher', 'JSONPOST', $api);
	}

	public function increaseFriendsCount($uID)
	{
		$friends = $this->getNumberOfFriends($uID) + 1;
		$this->setNumberOfFriends($uID, $friends);
	}

    /**
     * List all friend requests
     * 
     * @param $userid
     * @return array
     */
    public function userFriendRequests($uID)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$uID.'/relationships/in/friendRequest', 'GET');
                
        $i = 0;
        foreach($rels as $follower){
            $st = explode('/', $follower['start']);
            $user = $this->userDetailsById(end($st));
            
            $rtn[$i]['userid'] = end($st);
            $rtn[$i]['username'] = $user['username'];
            $rtn[$i]['firstname'] = $user['firstname'];
            $rtn[$i]['lastname'] = $user['lastname'];
        $i++;
        }
    
        if(isset($rtn)){
            return $rtn;
        }
    }
    
    /**
     * List all friends of user.
     * 
     * @param $userid
     * @return array
     */
    public function getFriendsList($uID)
    {
        $graphModule = new Graph();
        $rels = $graphModule->neo4japi('node/'.$uID.'/relationships/in/friendOf', 'GET');
                
        $i = 0;
        foreach($rels as $follower){
            $st = explode('/', $follower['end']);
            $user = $this->userDetailsById(end($st));
            if(end($st) === $uID){
                $st = explode("/", $follower['start']);
                $user = $this->userDetailsById(end($st));
            }
            $rtn[$i]['userid'] = end($st);
            $rtn[$i]['username'] = $user['username'];
            $rtn[$i]['firstname'] = $user['firstname'];
            $rtn[$i]['lastname'] = $user['lastname'];
        $i++;
        }
    
        if(isset($rtn)){
            return $rtn;
        }
    }

	/*********************************************************/
	/** USER-GROUP RELATED
    /**********************************************************/
    
    
	/**
	 * This method takes as inputs a user ID and a group ID and adds a FOLLOWER_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 * @return boolean
	 */
	public function addGroupFollower($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		return $succ;
	}

	public function deleteGroupFollower($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->deleteConnection($uID, $gID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a ADMIN_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 * @return boolean
	 */
	public function addGroupAdmin($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		return $succ;
	}

	public function deleteGroupAdmin($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->deeleteConnection($uID, $gID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a MEMBER_OF edge to neo4j.
	 * @access public
	 * @param uID, gID
	 * @return boolean
	 */
	public function addGroupMember($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'MEMBER_OF';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		return $succ;
	}

	public function deleteGroupMember($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'MEMBER_OF';
		$succ = $graph->deleteConnection($uID, $gID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a group ID and adds a INVITED_TO edge to neo4j.
	 * @access public
	 * @param uID, gID
	 * @return boolean
	 */
	public function addUserGroupInvitation($uID, $gID)
	{
		$graph = new Graph();
		$connectionType = 'INVITED_TO';
		$succ = $graph->addConnection($uID, $gID, $connectionType);
		return $succ;
	}

	/*********************************************************/
	/**********************************************************/

	/*********************************************************/
	/** USER-EVENT RELATED
	 * /**********************************************************/
	/**
	 * This method takes as inputs a user ID and a event ID and adds a FOLLOWER_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventFollower($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventFollower($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a event ID and adds a ORGANISER_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventOrganiser($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventOrganiser($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ORGANISER_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function changeEventOrganiser($uID, $uID2, $pID)
	{
		$graph = new Graph();
		$succ = $this->deleteEventOrganiser($uID, $pID);
		if ($succ == 1)
			$succ2 = $this->addEventOrganiser($uID2, $pID);
	}

	/**
	 * This method takes as inputs a user ID and a event ID and adds a ATTENDEE_OF edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addEventAttendee($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}

	public function deleteEventAttendee($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->deleteConnection($uID, $eID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and an event ID and adds an INVITED_TO edge to neo4j.
	 * @access public
	 * @param uID, eID
	 * @return boolean
	 */
	public function addUserEventInvitation($uID, $eID)
	{
		$graph = new Graph();
		$connectionType = 'INVITED_TO';
		$succ = $graph->addConnection($uID, $eID, $connectionType);
		return $succ;
	}
	/*********************************************************/
	/**********************************************************/

	/*********************************************************/
	/** USER-PAGE RELATED
	 * /**********************************************************/
	/**

	 * /**
	 * This method takes as inputs a user ID and a page ID and adds a FOLLOWER_OF edge to neo4j.
	 * @access public
	 * @param uID, pID
	 * @return boolean
	 */
	public function addPageFollower($uID, $pID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->addConnection($uID, $pID, $connectionType);
		return $succ;
	}

	public function deletePageFollower($uID, $pID)
	{
		$graph = new Graph();
		$connectionType = 'FOLLOWER_OF';
		$succ = $graph->deleteConnection($uID, $pID, $connectionType);
		return $succ;
	}

	/**
	 * This method takes as inputs a user ID and a page ID and adds a ADMIN_OF edge to neo4j.
	 * @access public
	 * @param uID, pID
	 * @return boolean
	 */
	public function addPageAdmin($uID, $pID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->addConnection($uID, $pID, $connectionType);
		return $succ;
	}

	public function deletePageAdmin($uID, $pID)
	{
		$graph = new Graph();
		$connectionType = 'ADMIN_OF';
		$succ = $graph->deleteConnection($uID, $pID, $connectionType);
		return $succ;
	}

	public function changePageAdmin($uID, $uID2, $pID)
	{
		$graph = new Graph();
		$succ = $this->deletePageAdmin($uID, $pID);
		if ($succ == 1)
			$succ2 = $this->addPageAdmin($uID2, $pID);
	}

	/**
	 * This method takes as inputs a user ID and an institution ID and adds a ATTENDEE_OF edge to neo4j.
	 * @access public
	 * @param uID, iID
	 * @return boolean
	 */
	public function addInstitutionAttendee($uID, $iID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->addConnection($uID, $iID, $connectionType);
		return $succ;
	}

	public function deleteInstitutionAttendee($uID, $iID)
	{
		$graph = new Graph();
		$connectionType = 'ATTENDEE_OF';
		$succ = $graph->deleteConnection($uID, $iID, $connectionType);
		return $succ;
	}
}

?>
