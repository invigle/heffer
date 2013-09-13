<?php
 
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language;
 
/**
 * FrontPage - This renders the front home page
 * 
 * @package heffer
 * @author Gavin Hanson
 * @copyright 2013
 * @version $Id$
 * @access public
 */
 
class FrontPage extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */    
    public function __construct(Language $theLanguage)
    {
        $this->_language = $theLanguage;
        parent::__construct();
        $this->_pageTitle = $this->_language->_frontPage['pageTitle'];
        echo $this->renderHeader();
        echo $this->renderTopNav();
        echo $this->registrationForm();
        echo $this->renderJSLinks();
    }
    
    private function renderJSLinks() {
        return '<script src="/assets/bootstrap/js/jquery.js"></script>
                <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>';
                //'<script src="/assets/bootstrap/js/collapse.js"></script>';
    }
    
    private function registrationForm()
    {       
        return '<div class="container">
                    <h3>'.$this->_language->_frontPage["register"].'</h3>
                    <div class="row-fluid">
                        <div class="col-md-6">
                            <input type="text" class="form-control col-md-6" name="firstname" value="" placeholder="'.$this->_language->_frontPage["firstname"].'">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control col-md-6" name="lastname" value="" placeholder="'.$this->_language->_frontPage["lastname"].'">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <input type="text" class="form-control col-md-12" name="email" value="" placeholder="'.$this->_language->_frontPage["emailaddress"].'">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <input type="text" class="form-control col-md-12" name="confirmemail" value="" placeholder="'.$this->_language->_frontPage["confirmemailaddress"].'">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-md-6">
                            <input type="text" class="form-control col-md-6" name="username" value="" placeholder="'.$this->_language->_frontPage["username"].'">
                        </div>
                        <div class="col-md-6">
                            <input type="password" class="form-control col-md-6" name="password" value="" placeholder="'.$this->_language->_frontPage["password"].'">
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="col-md-6">
                            <h4>'.$this->_language->frontPage["birthdate"].'</h4>
                            <div class="row-fluid">
                                <div class="col-md-3">
                                    <input type="text" name="dob_day" class="form-control" placeholder="Day [DD]">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="dob_month" class="form-control" placeholder="Month [MM]">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="dob_year" class="form-control" placeholder="Year [YYYY]">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>'.$this->_language->frontPage["gender"].'</h4>
                            <div class="row-fluid">
                                <div class="col-md-6">
                                    <div class="radio">
                                      <label>
                                          <input type="radio" name="gender" id="gender_male" value="male" checked>
                                          Male
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>
                                        <input type="radio" name="gender" id="gender_female" value="female" checked>
                                        Female
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    
 }
 
 ?>