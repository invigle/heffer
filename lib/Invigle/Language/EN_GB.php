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
            'month' => "Month",
            'day' => "Day",
            'year' => "Year",
            'registerButton' => "Get Started"
        );
    }
}

?>