<?php

/**
 * @author Gavin Hanson
 * @copyright 2013
 */
 
 namespace Invigle\FrontEndUIs;
 
 use Invigle\FrontEndUIs;
 
 class FrontPage extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */
    public function __construct($pageTitle)
    {
        $this->_pageTitle = $pageTitle;
        echo $this->renderHeader();
    }
    
 }