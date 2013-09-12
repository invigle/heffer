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
     
    public function __construct(Language $language)
    {
        $this->_pageTitle = $language->_frontPage['pageTitle'];
        echo $this->renderHeader();
        echo $this->renderTopNav();
    }
    
 }
 
 ?>