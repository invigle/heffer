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
	public $_firstName;
	/**
	 * @AttributeType string
	 * This holds the last name of the user
	 */
	public $_lastName;
	/**
	 * @AttributeType string
	 * This holds the location of the user
	 */
	public $_location;
	/**
	 * @AttributeType string
	 * This holds the email of the user
	 */
	public $_email;
	public $_password;
	public $_birthday;
	public $_institution;
	public $_relationshipStatus;
	public $_gender;
	public $_sexualPref;
	public $_uID;
	public $_profilePicID;
	public $_followerCount;
	public $_friendCount;
    
    /**
     * This method will check the graph database to ensure a username is unique.
     * @param username
     * @return boolean (true if Available, false if Taken)
     */
    public function validateUsername($username)
    {
        $graph = new Graph();
        
        $check['indexBy'] = "username";
        $check['indexValue'] = $username;
        $api = $graph->findNodeId($check);
        
        if($api){
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
        
        $check['indexBy'] = "email";
        $check['indexValue'] = $email;
        $api = $graph->findNodeId($check);
        
        if($api){
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
        if(strlen($username < "4")){
            return false;
        }elseif($username === "admin" || $username === "invigle" || $username === "staff"){
            return false;
        }else{
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
	public function addUser($aUserArray) {
		if (!$this->validateUsernameFormatting($aUserArray['username'])){
            return 'username-invalid';
            die('user invalid');
		}
        
        if (!$this->validateUsername($aUserArray['username'])) {
            return 'username-taken';
            die('user taken');
        }
        
        if (!$this->validateEmailFormatting($aUserArray['email'])) {
            return 'email-invalid';
            die('email invalid');
        }
        
        if (!$this->validateEmailAddress($aUserArray['email'])) {
            return 'email-taken';
            die('email taken');
        }
        
        die('Passed all tests');
        
        //Create the new user account in neo4j
        $graph = new Graph();
        $api = $graph->neo4japi('node', 'JSONPOST', $aUserArray);
        
        //Create Indexes for the User Account.
        $index['name'] = "firstname";
        $graph->neo4japi('index/node/', 'JSONPOST', $index);
        
        $index['name'] = "lastname";
        $graph->neo4japi('index/node/', 'JSONPOST', $index);
        
        $index['name'] = "email";
        $graph->neo4japi('index/node/', 'JSONPOST', $index);
        
        $index['name'] = "username";
        $graph->neo4japi('index/node/', 'JSONPOST', $index);
        
        
        $test = $graph->neo4japi('index/node/', 'GET');
        print '<pre>';
        print_r($test);
        print '</pre>';
                
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