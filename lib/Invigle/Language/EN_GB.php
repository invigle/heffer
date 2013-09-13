<?php

namespace Invigle\Language;

use Invigle\Language;

/**
 * EN_GB - This is the English (GB) language class
 * 
 * @package heffer
 * @author Grant Millar
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class EN_GB extends Language
{
    public function __construct()
    {
        $this->_frontPage = array(
            'pageTitle' => "Invigle - \"Let's Link Up!\"",
            'register' => "Register123",
            'firstname' => "First Name",
            'lastname' => "Last Name",
            'emailaddress' => "Email Address",
            'confirmemailaddress' => "Confirm Email Address",
            'username' => "Username",
            'password' => "Password",
            'birthdate' => "Date of Birth",
            'gender' => "Gender",
            'male' => "Male",
            'female' => "Female",
            'month' => "Month [MM]",
            'day' => "Day [DD]",
            'year' => "Year [YYYY]",
            'registerButton' => "Get Started",
            'error' => "Error",
            'username-taken' => "The username you have entered is already in use. Please try another.",
            'username-invalid' => "The username you have entered is invalid, usernames should be more than 4 characters long and not be numeric.",
            'email-taken' => "The email address you have entered is already in use. Please click Forgot Password to recover your password.",
            'email-invalid' => "The email address you have entered is invalid, please try again.",
            'dob-invalid' => "The date of birth you have entered is invalid, please ensure Day, Month and Year are Numeric entries.",
            'pw-too-short' => "The password you have entered is too short, you must use a minimum of 6 characters.",
            'firstname-too-short' => "Your First name must be at least 2 characters long.",
            'lasrname-too-short' => "Your Last name must be at least 2 characters long.",
            'name-is-numeric' => "First and Last names cannot be numeric.",
            'loginHere' => "Login Here",
            'login-now' => "Login Now",
            'login-failed' => "Your username and password did not match any user in our database.",
            'logged-in-as' => "Logged in as",
                        
        );
    }
}

?>