<?php

/**
 * @author Gavin Hanson
 * @copyright 2013
 */
 
namespace Invigle\FrontEndUIs;
 
use Invigle\FrontEndUIs,
    Invigle\Language;
 
class FrontPage extends FrontEndUIs {
    
    /**
     * Construct creates all the classes we need to render a page
     * @access public
     */
    public function __construct(Language $language)
    {
        $this->_pageTitle = $language->frontPage['pageTitle'];
        echo $this->renderHeader();
    }
    
 }