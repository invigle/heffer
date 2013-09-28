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
		$graphModule = new Graph();
		$count = $graphModule->countNodes('User', 'username', $username);
        
		if ($count) {
			return false;
		} else {
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
		$graphModule = new Graph();
        $count = $graphModule->countNodes('User', 'email', $email);
        
		if ($count)
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
		$graphModule = new Graph();
		$userId = $graphModule->createNode('User', $aUserArray);

		//Email the new User their login credentials?

		//Login the new User and forward them to a profile page.

		//return the New User ID.
		return $userId;
	}
    

	/**
	 * This method takes input as an array from $_POST and logs in a user.
	 */
	public function loginUser(array $userInput)
	{
		$graphModule = new Graph();
        $login = $graphModule->matchNode('User', 'email', $userInput['email']);

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
				//$updateDB['query'] = "MATCH n:User WHERE n.email = '$userInput[email]' SET n.sessionid='$_SESSION[sid]' SET n.ipaddress='$_SERVER[REMOTE_ADDR]' SET n.lastAction='" . time() . "' SET n.rememberme='" . $userInput['rememberme'] . "' RETURN n;";
				//$update = $graphModule->neo4japi('cypher', 'JSONPOST', $updateDB);
                
                $nodeProperties = array(
                    'sessionid'=>$_SESSION['sid'],
                    'ipaddress'=>$_SERVER['REMOTE_ADDR'],
                    'lastAction'=>time(),
                    'rememberme'=>$userInput['rememberme'],
                );
                $graphModule->updateNode('User', 'email', $userInput['email'], $nodeProperties);

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
		$graphModule = new Graph();

        $api = $graphModule->matchNode('User', 'sessionid', $_SESSION['sid']);
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
		$graphModule = new Graph();
        $api = $graphModule->matchNode('User', 'sessionid', $_SESSION['sid']);

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
		$graphModule = new Graph();
        $api = $graphModule->selectNodeById($uID);
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
		$graphModule = new Graph();
        
        $update['lastupdate'] = time();
        $graphModule->updateNodeById($userID, $update);
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
		foreach ($rels as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $followee)
			{
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
		foreach ($rels as $follower)
		{
			$st = explode('/', $follower['start']);
			$user = $this->userDetailsById(end($st));

			$rtn[$i]['userid'] = end($st);
			$rtn[$i]['username'] = $user['username'];
			$rtn[$i]['firstname'] = $user['firstname'];
			$rtn[$i]['lastname'] = $user['lastname'];
			$i++;
		}

		if (isset($rtn))
		{
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
		foreach ($rels as $follower)
		{
			$st = explode('/', $follower['end']);
			$user = $this->userDetailsById(end($st));

			if(isset($user['username'])){
                $rtn[$i]['userid'] = end($st);
    			$rtn[$i]['username'] = $user['username'];
    			$rtn[$i]['firstname'] = $user['firstname'];
    			$rtn[$i]['lastname'] = $user['lastname'];
    			$i++;
            }
		}

		if (isset($rtn))
		{
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
		$graphModule = new Graph();

		//Add the relationship between follower and followee.
		$api = $graphModule->addConnection($follower, $followee, 'followerOf');
        
        $createProperties = array(
            'actionType'=>'followerOf',
            'timestamp'=>time(),
            'uid'=>$followee,
        );
        
        $actionId = $graphModule->createNode('Action', $createProperties);
                
		//Add a relationship from follower to action node.
        $this->updateUserTimeline($follower, $actionId);

		//Update the Users last action timestamp.
		$this->updateUserTimestamp($follower);

		//Update Number of Followers
		$this->increaseFollowersCount($followee);
	}

	public function getNumberOfFollowers($uID)
	{
		$graphModule = new Graph();
		$apiCall = $graphModule->selectNodeById($uID);
		$user = $apiCall['data'][0][0]['data'];

		return $user['followerCount'];
	}

	public function setNumberOfFollowers($uID, $count)
	{
		$graphModule = new Graph();
		$this->_followerCount = $count;
        
        $properties['followerCount'] = $count;
        $graphModule->updateNodeById($uID, $properties);
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
		foreach ($friends as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $followee)
			{
				return true;
				break;
			}
		}

		$frReqs = $graphModule->neo4japi('node/'.$follower.'/relationships/all/friendRequest', 'GET');
		foreach ($frReqs as $rel)
		{
			$en = explode("/", $rel['end']);
			if (end($en) === $followee)
			{
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
        $friendProperties1 = array(
            'actionType'=>'friendOf',
            'timestamp'=>time(),
            'uid'=>$uID2,
        );
        $actionId = $graphModule->createNode('Action', $friendProperties1);

		//Now add it the other way around.
		$friendProperties2 = array(
            'actionType'=>'friendOf',
            'timestamp'=>time(),
            'uid'=>$uID1,
        );
        $actionIdD = $graphModule->createNode('Action', $friendProperties2);

		//Add a relationship from follower to action node.
        $this->updateUserTimeline($uID1, $actionId);

		//Add a relationship from friend->user to action node.
        $this->updateUserTimeline($uID2, $actionIdD);

		//Update the Users last action timestamp.
		$this->updateUserTimestamp($uID1);
		$this->updateUserTimestamp($uID2);

		//Update both users friend count.
		$this->increaseFriendsCount($uID1);
		$this->increaseFriendsCount($uID2);

	}

	public function getNumberOfFriends($uID)
	{
		$graphModule = new Graph();
        $apiCall = $graphModule->selectNodeById($uID);
		$user = $apiCall['data'][0][0]['data'];

		return $user['friendCount'];
	}

	public function setNumberOfFriends($uID, $count)
	{
		$graphModule = new Graph();
		$this->_friendCount = $count;
        
        $update['friendCount'] = $count;
        $graphModule->updateNodeById($uID, $update);
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
		foreach ($rels as $follower)
		{
			$st = explode('/', $follower['start']);
			$user = $this->userDetailsById(end($st));

			$rtn[$i]['userid'] = end($st);
			$rtn[$i]['username'] = $user['username'];
			$rtn[$i]['firstname'] = $user['firstname'];
			$rtn[$i]['lastname'] = $user['lastname'];
			$i++;
		}

		if (isset($rtn))
		{
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
		$rels = $graphModule->neo4japi('node/'.$uID.'/relationships/in/friendOf',
			'GET');

		$i = 0;
		foreach ($rels as $follower)
		{
			$st = explode('/', $follower['end']);
			$user = $this->userDetailsById(end($st));
			if (end($st) === $uID)
			{
				$st = explode("/", $follower['start']);
				$user = $this->userDetailsById(end($st));
			}
			$rtn[$i]['userid'] = end($st);
			$rtn[$i]['username'] = $user['username'];
			$rtn[$i]['firstname'] = $user['firstname'];
			$rtn[$i]['lastname'] = $user['lastname'];
			$i++;
		}

		if (isset($rtn))
		{
			return $rtn;
		}
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

    /**
     * Update User Timeline
     * This function will remove the current last timeline edge and replace the latest node + add new edges.
     * 
     * @param $userId, $newAction (ID Of Action)
     * @return none
     */
    public function updateUserTimeline($userId, $newAction)
    {
        $graphModule = new Graph();
        
        //Get old Connection
        $old = $graphModule->neo4japi("node/".$userId."/relationships/out/timeline");
        $ol = explode("/", $old[0]['end']);
        $oldConnectionId = end($ol);
        
        //Remove Old Connection from [User]--To(1)-->[oldAction]
        $graphModule->deleteConnection($userId, $oldConnectionId, 'timeline');
        
        //Add Connections 1 & 2 Between User + NewAction + OldAction.
        $graphModule->addConnection($userId, $newAction, 'timeline');
        $graphModule->addConnection($newAction, $oldConnectionId, 'timeline');
    }

}


?>
