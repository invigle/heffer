<?php

namespace Invigle;

/**
 * @access public
 * @author Grant
 */
class User {
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
        
        if($api['data'][0][0] >= "1"){
            return false;
        }else{
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
        
        if($api['data'][0][0] >= "1"){
            return false;
        }else{
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
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }else{
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
        if(strlen($username) < "4"){
            return false;
            break;
        }elseif(is_numeric($username)){
            return false;
            break;
        }elseif($username === "admin" || $username === "invigle" || $username === "staff"){
            return false;
            break;
        }else{
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
	public function addUser($aUserArray) {
		if (!$this->validateUsernameFormatting($aUserArray['username'])){
            return 'username-invalid';
		}
        
        if (!$this->validateUsername($aUserArray['username'])) {
            return 'username-taken';
        }
        
        if (!$this->validateEmailFormatting($aUserArray['email'])) {
            return 'email-invalid';
        }
        
        if (!$this->validateEmailAddress($aUserArray['email'])) {
            return 'email-taken';
        }
        
        //Create the new user account in neo4j
        $graph = new Graph();    
        
        $queryString = "";
        foreach($aUserArray as $key => $value){
            $queryString.= "$key : \"$value\", ";
        }
        $queryString = substr($queryString, 0, -2);
        $user['query'] = "CREATE (n:User {".$queryString."}) RETURN n;";   
            
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
        
        if(isset($login['data'][0][0]['data'])){
            $userData = $login['data'][0][0]['data'];
            
            //Extract the users ID# from the database
            $bit = explode("/", $login['data'][0][0]['self']);
            $usersID = end($bit);
            
            $inputPassword = hash('sha256', CONF_SECURITYSALT.$userInput['password']);
            
            if($inputPassword === $userData['password']){               
                /* Handle Users Session */
                //This function generates a new function to prevent screenwatch driven session hijacks.
                session_regenerate_id();
                
                //Set the sessions based on session_id();
                $_SESSION['uid'] = $usersID;
                $_SESSION['sid'] = session_ID();
                
                if(!isset($userInput['rememberme'])){
                    $userInput['rememberme'] = "no";
                }
                
                //Store the users logged in status in Neo4j.
                $updateDB['query'] = "MATCH n:User WHERE n.email = '$userInput[email]' SET n.sessionid='$_SESSION[sid]' SET n.ipaddress='$_SERVER[REMOTE_ADDR]' SET n.lastAction='".time()."' SET n.rememberme='".$userInput['rememberme']."' RETURN n;";
                $update = $graph->neo4japi('cypher', 'JSONPOST', $updateDB);
                                
                return true;
            }else{
                return false;
            }
            
            
        }else{
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
        
        if(isset($api['data'][0][0]['data'])){
            $userInfo = $api['data'][0][0]['data'];
            if($userInfo['ipaddress'] !== $_SERVER['REMOTE_ADDR']){
                //IP Does not match session, Kick this fool out!
                $_SESSION['sid'] = "";
                $_SESSION['uid'] = "";
                session_destroy();
                return false;
            }else{
                //This guys for true, let em' stay.
                return true;
            }
        
        }else{
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
	public function deleteUser($aUID) {
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
	public function editUser($aUserArray) {
		// Not yet implemented
	}
}
?>