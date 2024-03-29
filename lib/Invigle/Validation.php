<?php
 
namespace Invigle;
 
/**
 * Validation - Validate form input.
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class Validation {
    
    /**
     * Validate email input
     * 
     * @params $email, $confirmemail
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validateEmailAddress($email, $confirmemail)
    {
        if($email !== $confirmemail){
            $rtn = array(
                        'status'=>false,
                        'error'=>"emails-dont-match"
                    );
            return $rtn;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"email-invalid"
                    );
            return $rtn;
        }
        
    $rtn['status'] = true;
    return $rtn;
    }
    
    /**
     * Validate username input
     * @param $username
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validateUsername($username){
        if(strlen($username) <= "2"){
            $rtn = array(
                        'status'=>false,
                        'error'=>"username-invalid"
                    );
            return $rtn;
        }
        
        if(is_numeric($username)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"username-invalid"
                    );
            return $rtn;
        }
        
        if($username === "invigle" || $username === "admin" || $username === "staff"){
            $rtn = array(
                        'status'=>false,
                        'error'=>"username-invalid"
                    );
            return $rtn;
        }
        
    $rtn['status'] = true;
    return $rtn;
    }
    
    /**
     * Validate password input
     * 
     * @param $password, $confirmpw
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validatePassword($password, $confirmpw)
    {
        //Form was submitted, lets do some checks...
        if($password !== $confirmpw){
            $rtn = array(
                        'status'=>false,
                        'error'=>"pw-dont-match"
                    );
            return $rtn;
        }
        
        if(strlen($password) < "6"){
            $rtn = array(
                        'status'=>false,
                        'error'=>"pw-too-short"
                    );
            return $rtn;
        }
        
        if(is_numeric($password)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"pw-is-numeric"
                    );
            return $rtn;
        }
    
    
    //We havn't left yet? Cool, then the password must be good!
    $rtn['status'] = true;
    return $rtn;
    }
    
    /**
     * Validate date of birth input
     * 
     * @params $dob_day, $dob_month, $dob_year
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validateDateOfBirth($dob_day, $dob_month, $dob_year)
    {
        //Confirm that all dob inputs are numeric.
        if(!is_numeric($dob_day) || !is_numeric($dob_month) || !is_numeric($dob_year)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"dob-invalid"
                    );
                    
            return $rtn;
        }
        
        //Check that the day input is no higher than 31
        if($dob_day > "31"){
            $rtn = array(
                        'status'=>false,
                        'error'=>"dob-day-invalid"
                    );
                    
            return $rtn;
        }
        
        //Check that the month input is no higher than 12
        if($dob_month > "12"){
            $rtn = array(
                        'status'=>false,
                        'error'=>"dob-month-invalid"
                    );
                    
            return $rtn;
        }
        
        
        //Check that the user is older than 13 (or close enough)
        if($dob_year > date('Y') - 13){
            $rtn = array(
                        'status'=>false,
                        'error'=>"dob_year_invalid"
                    );
                    
            return $rtn;
        }
    
    $rtn['status'] = true;
    return $rtn;
    }
    
    /**
     * validate firstname input
     * 
     * @param $firstname
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validateFirstName($firstname){
        if(strlen($firstname) < "2"){
            $rtn = array(
                    'status'=>false,
                    'error'=>"firstname-too-short"
                );
                
            return $rtn;
        }
        if(is_numeric($firstname)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"name-is-numeric"
                    );
            
            return $rtn;
        }
        
    $rtn['status'] = true;
    return $rtn;
    }
    
    /**
     * validate lastname input
     * 
     * @param $lastname
     * @return array (status = true/false, error = if an error is present then the 'language' representation key is passed.)
     */
    public function validateLastName($lastname){
        if(strlen($lastname) < "2"){
            $rtn = array(
                    'status'=>false,
                    'error'=>"lastname-too-short"
                );
                
            return $rtn;
        }
        if(is_numeric($lastname)){
            $rtn = array(
                        'status'=>false,
                        'error'=>"name-is-numeric"
                    );
            
            return $rtn;
        }
        
    $rtn['status'] = true;
    return $rtn;
    }
    
    
 }
 
 ?>