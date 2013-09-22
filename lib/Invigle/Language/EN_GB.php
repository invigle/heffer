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
            'register' => "Register",
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
            'loginHere' => "Login Here",
            'login-now' => "Login Now",
            'login-failed' => "Your username and password did not match any user in our database.",
            'logged-in-as' => "Logged in as",
            'my-details' => "My Details",
            'followers' => "Followers",
            'friends' => "Friends",
            'my-profile' => "My Profile",   
        );
        
        $this->_accountdetails = array(
            'pageTitle' => "Invigle - My Details",
            'oldpw' => "Existing Password",
            'newpw' => "New Password",
            'confirmpw' => "Confirm new Password",
            'changepw' => "Change Password",
            'error' => "Error",
            'old-pw-wrong' => "The existing password you have entered did not match our records, please try again.",
            'pw-changed' => "Your password has been changed successfully",
            'setup-profile' => "Setup Profile",
            'save-profile' => "Save Profile",
            'firstname' => "First Name",
            'lastname' => "Last Name",
            'emailaddress' => "Email Address",
            'username' => "Username",
            'password' => "Password",
            'birthdate' => "Date of Birth",
            'gender' => "Gender",
            'male' => "Male",
            'female' => "Female",
            'relationshipstatus' => "Relationship Status",
            'sexualpref' => "Sexual Preference",
            'location' => "Location",
            'institution'=> "Institution",
            'profileupdated' => "Your profile has been updated successfully.",
        );
        
        
        $this->_inputValidation = array(
            'username-taken' => "The username you have entered is already in use. Please try another.",
            'username-invalid' => "The username you have entered is invalid, usernames should be more than 4 characters long and not be numeric.",
            'email-taken' => "The email address you have entered is already in use. Please click Forgot Password to recover your password.",
            'email-invalid' => "The email address you have entered is invalid, please try again.",
            'dob-invalid' => "The date of birth you have entered is invalid, please ensure Day, Month and Year are Numeric entries.",
            'pw-dont-match' => "The new password and confirm password fields did not match, please try again.",
            'pw-too-short' => "Your new password must be at least 6 characters long, please try again",
            'pw-is-numeric' => "Your new password must not be entirely numeric, please try again",
            'firstname-too-short' => "Your First name must be at least 2 characters long.",
            'lasrname-too-short' => "Your Last name must be at least 2 characters long.",
            'name-is-numeric' => "First and Last names cannot be numeric.",
            'emails-dont-match' => "The email addresses you entered did not match, please try again.",
            'dob-day-invalid' => "The day of your Date of Birth must be between 1 and 31.",
            'dob-month-invalid' => "The month of your Date of Birth must be between 1 and 12.",
            'dob_year_invalid' => "Your year of birth must be within the last 100 years excluding the last 13.",
        );
        
        $this->_userProfile = array(
            'user-not-found' => "We were unable to locate that user, please try again.",
            'relationshipstatus' => "Relationship status",
            'follow' => "Follow",
            'addfriend' => "Add Friend",
            'nowfollowing' => "You are now following",
            'being-followed-by' => "is being followed by",
            'nobody' => "Nobody",
            'is-following' => "is following",
            'already-following' => "Already following",
            'error-cannot-follow' => "You are not able to follow somebody more than once.",
            'friend-request-sent' => "Friend request sent successfully.",
            'you-are-now-friends-with' => "You are now friends with",
            'already-friends' => "Already Friends",
            'is-friends-with' => "is friends with",
            'cannot-befriend' => "You are already friends, Cannot continue.",
        );
        
        $this->_timeline = array(
            'timeline' => "Timeline",
            'is-friends-with' => "Is now friends with",
            'started-following' => "Started following",
        );
    }
}

?>