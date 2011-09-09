<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Smarty_IndexController
 *
 * @author Administrador
 */
class Smarty_IndexController extends SMVC_ControllerAbstract{

    public function indexAction(){

        $this->_pageTitle = 'Smart MVC - Smarty_IndexController->indexAction();';

        $var = 'var de teste ';

        //corresponde ao assign
        $this->toView('var', $var);

    }

}
