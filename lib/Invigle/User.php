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
	 * This method will check that the entered email address is valid.
	 * @param email
	 * @return boolean (true if Valid, false if Not)
	 */
	public function validateEmailFormatting($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return false;
		} else
		{
			return true;
		}
	}

	/**
	 * This method will check that the entered username is valid
	 * @param username
	 * @return boolean
	 */
	public function validateUsernameFormatting($username)
	{
		//Rules
		if (strlen($username) < "2")
		{
			return false;
			break;
		} elseif (is_numeric($username))
		{
			return false;
			break;
		} elseif ($username === "admin" || $username === "invigle" || $username ===
		"staff")
		{
			return false;
			break;
		} else
		{
			return true;
			break;
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
		if (!$this->validateUsernameFormatting($aUserArray['username']))
		{
			return 'username-invalid';
		}

		if (!$this->validateUsername($aUserArray['username']))
		{
			return 'username-taken';
		}

		if (!$this->validateEmailFormatting($aUserArray['email']))
		{
			return 'email-invalid';
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


	/*********************************************************/
	/** USER-USER RELATED
	 * /**********************************************************/
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

	public function getNumberOfFollowers()
	{
		return $this->_followerCount;
	}

	public function setNumberOfFollowers($count)
	{
		$this->_followerCount = $count;
	}


	public function increaseFollowersCount($uID)
	{
		$followers = $uID->getNumberOfFollowers() + 1;
		$uID->setNumberOfFollowers($followers);
	}

	public function unfollowUser($follower, $followee)
	{
		// to be implemented
	}

	/**
	 * This method takes as inputs a two users' IDs and adds a FRIEND_OF edge to neo4j.
	 * @access public
	 * @param uID, uID2
	 * @return boolean
	 */
	public function addFriend($uID, $uID2)
	{
		$graph = new Graph();
		$connectionType = 'FRIEND_OF';
		$succ = $graph->addConnection($uID, $uID2, $connectionType);
		return $succ;
	}

	public function deleteFriend($uID, $uID2)
	{
		$graph = new Graph();
		$connectionType = 'FRIEND_OF';
		$succ = $graph->deleteConnection($uID, $uID2, $connectionType);
		return $succ;
	}
	/*********************************************************/
	/**********************************************************/


	/*********************************************************/
	/** USER-GROUP RELATED
	 * /**********************************************************/
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
