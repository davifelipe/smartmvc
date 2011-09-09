<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Davi
 */
class IndexController extends SMVC_ControllerAbstract{

    
    public function indexAction(){
        
        $this->_pageTitle = 'Smart MVC - IndexController->indexAction();';

        $this->_pageDescription = 'Framework PHP';

        $var = 'var de teste ';

        $this->toView('var', $var);

    }

    public function viewphtmlAction(){

        $this->_pageTitle = 'Smart MVC - IndexController->viewphtmlAction();';

        $this->_pageDescription = 'viewphtml.phtml';

    }

    

}
