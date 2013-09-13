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
        return '<h3>'.$this->_language->_fontPage["register"].'</h3>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="firstname" value="" placeholder="'.$this->_language->_frontPage["firstname"].'">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="lastname" value="" placeholder="'.$this->_language->_frontPage["lastname"].'">
                    </div>
                </div>';
    }
    
 }
 
 ?>