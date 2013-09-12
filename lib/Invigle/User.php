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
    public function validateUsername($username, Graph $graph)
    {
<<<<<<< HEAD
<<<<<<< HEAD
        //$graph = new Graph();
        $check['indexBy'] = "username";
        $check['indexValue'] = $username;
        $api = $graph->findNodeId($check);
=======
=======
>>>>>>> ddcd4f6222403304cea4c3ffc3f36a730415c276
        $graph = new Graph();
        $check['query'] = "MATCH n:User WHERE n.username = \"$username\" RETURN count(*);";
        $api = $graph->neo4japi('cypher', 'JSONPOST', $check);
        
<<<<<<< HEAD
        print '<pre>';
        print_r($api);
        print '</pre>';
>>>>>>> 762c7b4d2377345a06fe1316e7b10cf48d75cfd9
        
=======
>>>>>>> ddcd4f6222403304cea4c3ffc3f36a730415c276
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
<<<<<<< HEAD
<<<<<<< HEAD
        $check['indexBy'] = "email";
        $check['indexValue'] = $email;
        $api = $graph->findNodeId($check);
=======
        
        $check['query'] = "MATCH n:User WHERE n.email = \"$email\" RETURN count(*);";
        $api = $graph->neo4japi('cypher', 'JSONPOST', $check);
>>>>>>> 762c7b4d2377345a06fe1316e7b10cf48d75cfd9
=======
        
        $check['query'] = "MATCH n:User WHERE n.email = \"$email\" RETURN count(*);";
        $api = $graph->neo4japi('cypher', 'JSONPOST', $check);
>>>>>>> ddcd4f6222403304cea4c3ffc3f36a730415c276
        
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
            //return 'username-invalid';
            //die('user invalid');
		}
        
        if (!$this->validateUsername($aUserArray['username'])) {
            //return 'username-taken';
            //die('user taken');
        }
        
        if (!$this->validateEmailFormatting($aUserArray['email'])) {
            //return 'email-invalid';
            //die('email invalid');
        }
        
        if (!$this->validateEmailAddress($aUserArray['email'])) {
            //return 'email-taken';
            //die('email taken');
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
        
  
    return $apiCall;
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