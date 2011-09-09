<?php

/**
 * processes from the get parameters
 * @version 1.0
 * @author davifelipe<davifelipems@hotmail.com>
 */
class SMVC_Request{

    /**
     * module stores the past by get
     * letar with first capital letter and '_' after
     * ex: Admin_
     * @var <String>
     * @name $module
     */
    private static $module;

     /**
     * stores the tab url
     * @var <String>
     */
     private static $separator;

    /**
     * stores the template name
     * @var <String>
     */
     private static $template;

     /**
      * Stores type of execution 'action' or 'render'
      * @var <String>
      */
     private static $rumTimeType;

    /**
     * indicates whether this template enabled
     * @var <boolean>
     */
    private static $templateOn;

    /**stores the current controller*/
    private static $currentController;

    /**stores the current action*/
    private static $currentAction;

    /**Name of the current controller object*/
    public static $controllerObjetc;

    /**
     * $ _GET array fills with parameters passed
     * @return void
     * @access private
     */
    private static function paramToGet($param){
        if(substr_count($param,"=") > 0){

              $pos = strripos($param, '=');

                        //takes what comes before "="
              $antes = substr($param, 0, $pos);

              $tAntes = strlen($antes);
              $tValue = strlen($param);

              $dif = $tValue-$tAntes;

              $depois = substr($param, $pos+1, $dif);

              $_GET[$antes]=$depois;
        }
        
    }

    
    private static function init(){

        self::$separator = '/';

    }

    private static function processAcrion($action ,$index = false){

        self::init();

       
            $tBefore = strlen($action);
            
            
            $actionBefore = $action;

            
            $pos = strripos($action, '?');

            
            $action = substr($action, 0, $pos);

            
            $ttAfter = strlen($action);

            
            $difference = $tBefore-$ttAfter;

            
            $param = substr($actionBefore, $pos+1, $difference);
            

            if(substr_count($param,"&") > 0){
                $arrayParam = explode('&', $param);

                foreach ($param as $key => $value) {

                        self::paramToGet($value);
                }

            }else{

                self::paramToGet($param);
            }

            if($index){

                
                
                $controller = $action.'/index';
                
				self::$currentController = $action;

				self::$currentAction = 'index';

                $action = $controller;
               
                
            }else{
                $array = explode(self::$separator, $action);

               
                $controller = $array[0].'/'.$array[1];

				self::$currentController = $array[0];

				self::$currentAction = $array[1];

                $action = $controller;
            }


            return $action;
    }

    private static function setTemplate($_GET){
        $arrayParam = self::getArrayParam($_GET);
         self::$template = $arrayParam[0];

         foreach ($arrayParam as $key => $value) {
            if($value == 'off'){
                self::$templateOn = false;
                unset($arrayParam[$key]);
                $strParam = implode(self::$separator, $arrayParam);
                $_GET[$parametro] = $strParam;
            }
        }
              
    }

   

    /**
     * parameter removes the module
     * @param $_GET array Global $_GET
     * @return Array $_GET if the module parameters
     */
    private static function removeModule($_GET){

        $parametro = SMVC_Start::getActionParam();

        $arrayParam = self::getArrayParam($_GET);


        $dirTemplate = SMVC_Start::getDirTemplate();
        $dirTemplate = $dirTemplate.'/';
        $dirTemplate = str_replace('//', '/', $dirTemplate);

        $pointer = $dirTemplate.$arrayParam[0].'.phtml';

        $pointerTpl = $dirTemplate.$arrayParam[0].'.tpl';

        foreach ($arrayParam as $key => $value) {
            if($value == 'off'){
                unset($arrayParam[$key]);
            }
         }

        if(file_exists($pointer) || file_exists($pointerTpl)){
            self::$module = ucwords($arrayParam[0]).'_';
                unset ($arrayParam[0]);
                
        }else if(self::$rumTimeType == 'action'){
          self::$module = null;
        }
            
           $strParam = implode(self::$separator, $arrayParam);
           $_GET[$parametro] = $strParam;
        

            return $_GET;
    }

     /**
     * Automatically includes views of the respective processing controlers get parameters coming through
     * @param Array $_GET 
     * @param [optional]String $viewDir directory of files that will be loaded. taking as
     * references the www or public_html folder
     *  ex: for $vewDir= '../app/default/vew/' and controller= teste/teste will be load ../modules/default/vew/teste/teste.phtml
     * @return path of content file
     */
    public static function startMvc($_GET, Smarty $smarty = null, $viewDir=null){

    
        $_GET = self::removeModule($_GET);

       if($viewDir == null){
           $viewDir = self::getViewDir();
       }else{
            $viewDir = $viewDir.'/';
            $viewDir = str_replace('//', '/', $viewDir);

       }

      
       
       $param = SMVC_Start::getActionParam();
       
       $action = $_GET[$param];
       
       $arrayAction = explode(self::$separator, $action);
       
       $diference = (count($arrayAction))-2;

       switch ($diference) {
           case 0:
              $_GET[$arrayAction[0]] = $arrayAction[1];

           break;

           case 1:
              $_GET[$arrayAction[1]] = $arrayAction[2];

           break;

           case 2:
              $_GET[$arrayAction[2]] = $arrayAction[3];

           break;

       }

       $action = str_replace('__', '', $action);

   
        //tests whether the parameter is to get action
    if(isset($action) && $action != ''){
               
      
        //if it comes separator and ?
        if(substr_count($action,  self::$separator) > 0
        && substr_count($action,"?") > 0
        ){
             
            $controller = self::processAcrion($action);
            $action = $controller;

           //if  it's comes only ?
        }else if(substr_count($action,"?") > 0){
               
            $controller = self::processAcrion($action,$index = true);
            $action = $controller;

        }else if(substr_count($action,self::$separator) > 0){
           
            
            $action = str_replace(self::$separator, '/', $action);

            

           $controller  = $action;

		   $aUrl = explode('/', $controller);

		   self::$currentController = $aUrl[0];

		   self::$currentAction = $aUrl[1];

        }else{
            
            
            //if it comes to controller assigns
            $controller = $action.'/index';

            //contributes to action to load the controller
             $action = $controller;

        }

         //tests whether the controller exists

         $pointer =$viewDir.$controller.'.phtml';

         $pointerTpl =$viewDir.$controller.'.tpl';

        
         if(!file_exists($pointer)){
             
             #SMVC_Start::throwException(SMVC_Locale::getContent(4).$pointer);
               
         }

             
        
    }else{//if no action comes
        
        $controller = 'index/index';

         $s= SMVC_Start::getPathSeparator();
         //tests if index exists
         $pointer = $viewDir."index{$s}index.phtml";

         $pointerTpl = $viewDir."index{$s}index.tpl";

         if(!file_exists($pointer)){
             
             #SMVC_Start::throwException(SMVC_Locale::getContent(4).$pointer);
         }

    }

        
   
             /*
             * views actions
             */
             
        switch ($action) {

        case $controller:
			
            $cPath = explode('/', $controller);
            
            $cPath[0] = ucwords($cPath[0]);
            
             $act = $cPath[1].'Action';
             
           
            if(self::$rumTimeType == 'action'){
		
                 $ctrName = self::$module.$cPath[0].'Controller';
                self::$controllerObjetc = new $ctrName();

                if(!is_a(self::$controllerObjetc, 'SMVC_ControllerAbstract')){
                     SMVC_Start::throwException(SMVC_Locale::getContent(13));
                }

                self::$controllerObjetc->setModule(self::$module);
                self::$controllerObjetc->setController(self::$currentController);
                self::$controllerObjetc->setAction(self::$currentAction);

                
                foreach ($_GET as $key => $value) {
                      self::$controllerObjetc->_params[$key]=$value;
                }

                foreach ($_POST as $key => $value) {
                       self::$controllerObjetc->_params[$key]=$value;
                }
                
                self::$controllerObjetc->$act();
                
            }

            foreach ($_POST as $key => $value) {
                $value = self::removeSlashes($value);
               
                if(isset($smarty)){
                    $smarty->assign($key, $value);
                    
                }
            }
           
            if(self::$module == null){
                self::$module = 'default';
            }

                $s= SMVC_Start::getPathSeparator();

                $cPathOrign = explode('/', $controller);

                $compl = $cPathOrign[0].$s.$cPathOrign[1];

                if(file_exists($tplFile) && self::$rumTimeType == 'render'){

                            $smarty->template_dir = $smartTemplateDir;

			    return $tplFile;
                    
                }else if(file_exists($viewDir.$compl.'.phtml') && self::$rumTimeType == 'render'){
                       
                       return $viewDir.$compl.'.phtml';
                     
                }
              
           
            break;
            //action if it does not try to include index
        default:

            $ctrName = self::$module.'IndexController';
            self::$controllerObjetc = new $ctrName();
            if(self::$rumTimeType == 'action'){
		
                if(!is_a(self::$controllerObjetc, 'SMVC_ControllerAbstract')){
                     SMVC_Start::throwException(SMVC_Locale::getContent(13));
                }

                self::$controllerObjetc->setModule(self::$module);
                self::$controllerObjetc->setController(self::$currentController);
                self::$controllerObjetc->setAction(self::$currentAction);

                foreach ($_GET as $key => $value) {
                      self::$controllerObjetc->_params[$key]=$value;
                }

                foreach ($_POST as $key => $value) {
                       self::$controllerObjetc->_params[$key]=$value;
                }

                self::$controllerObjetc->indexAction();
                
            }
            


                foreach ($_POST as $key => $value) {
                    $value = self::removeSlashes($value);

                     if(isset($smarty)){
                        $smarty->assign($key, $value);
                    }

                }

                $s = SMVC_Start::getPathSeparator();

                $tplFile = $viewDir."index{$s}index.tpl";

                $smartTemplateDir = $viewDir.'index';

                $phtmlFile = $viewDir."index{$s}index.phtml";
                               
                #if you do not include template
                if(file_exists($tplFile) && self::$rumTimeType == 'render'){

			     $smarty->template_dir = $smartTemplateDir;
			     
                             return $tplFile;

                }else if(file_exists($phtmlFile) && self::$rumTimeType == 'render'){
                         
                        return $phtmlFile;
                }
            

        }
         

        if(file_exists($pointerTpl)){
            return $pointerTpl;
        }
            

    }

    private static function removeSlashes($str){

      if(is_string($str)){
            $str = str_replace('\\', '', $str);
      }
    
       return $str;
    }

    private static function getArrayParam($_GET){

        self::init();

        $param =  SMVC_Start::getActionParam();
        $contentParam = $_GET[$param];

        $arrayParam = explode(self::$separator, $contentParam);

        return $arrayParam;
    }

    /**
     * @return String the path of the directory view
      * from the application path
     */
    private static function getViewDir(){
        
        if(self::$module == null){
                $module = 'default';
        }else{
            $module = self::$module;
            $module = strtolower($module);
            $module = str_replace('_', '', $module);
        }

        $s = SMVC_Start::getPathSeparator();

        $viewDir = SMVC_Start::getAppDir()."{$s}{$module}{$s}views{$s}";
        $viewDir = str_replace("{$s}{$s}", $s, $viewDir);
        
        
        return $viewDir;
    }

    /**
     * load template
     * @param array $_GET array global
     * @param String $templateDir Directory where the template files
     */
    public static function startTemplate($_GET){
           
           $smarty = SMVC_Start::getSmarty();

          
            $smarty->compile_dir	= SMVC_Start::getDirTemplateCompiled();
            $smarty->template_dir	= SMVC_Start::getDirTemplate();
           
            $smarty->clear_compiled_tpl();

        $templateDir = SMVC_Start::getDirTemplate();

         self::$rumTimeType = 'action';

         self::startMvc($_GET);
         
         self::setTemplate($_GET);
         
        self::$templateOn = true;

        self::$templateOn = self::$controllerObjetc->_pageRender;
		 
        $s = SMVC_Start::getPathSeparator();

        $templateDir = $templateDir.$s;
        $templateDir = str_replace($s.$s, $s, $templateDir);

        $arrayParam = self::getArrayParam($_GET);
        
        $module = $arrayParam[0];

        if(self::$module!= ''){
                   $module = strtolower(self::$module);
                   $module = str_replace('_', '', $module);
        }
         
        if(self::$templateOn){
               
               #tpl
               if(file_exists($templateDir.$module.'.tpl')){
                   
                    $templateTpl =  $templateDir.$module.'.tpl';
                    
               }else if(file_exists($templateDir.'default.tpl')){
                    
                    $templateTpl =  $templateDir.'default.tpl';
               }

               #phtml
               if(file_exists($templateDir.$module.'.phtml')){
                    $template =  $templateDir.$module.'.phtml';

               }else if(file_exists($templateDir.'default.phtml')){

                    $template =  $templateDir.'default.phtml';
               }

        }else{
            //session_start();
        }
        
        $_pageTitle = self::$controllerObjetc->_pageTitle;
        $_pageDescription = self::$controllerObjetc->_pageDescription;
        $_pageKeyWords = self::$controllerObjetc->_pageKeyWords;
        self::$rumTimeType = 'render';
         
         if(!isset($template) && self::$templateOn){
           
             if(isset($templateTpl)){
             
                $smarty->assign('_pageDescription', self::$controllerObjetc->_pageDescription);
                $smarty->assign('_pageTitle', self::$controllerObjetc->_pageTitle);
		$smarty->assign('_pageKeyWords', self::$controllerObjetc->_pageKeyWords);

                
		$contentFile = self::startMvc($_GET,$smarty);
                $smarty->assign('_contentFile',$contentFile);
                                
                if(substr_count($templateTpl, '.tpl')>0) $smarty->display($templateTpl);

             }else{
                  SMVC_Start::throwException(SMVC_Locale::getContent(5).$module.".phtml"
                                            .SMVC_Locale::getContent(6).$templateDir);
             }

         }else if(isset($templateTpl)){
			
                $smarty->assign('_pageDescription', self::$controllerObjetc->_pageDescription);
                $smarty->assign('_pageTitle', self::$controllerObjetc->_pageTitle);
		$smarty->assign('_pageKeyWords', self::$controllerObjetc->_pageKeyWords);
				
				$contentFile = self::startMvc($_GET,$smarty);
				$smarty->assign('_contentFile',$contentFile);

               if(substr_count($templateTpl, '.tpl')>0) $smarty->display($templateTpl);
                
         }else if(isset($template)){

             foreach ($_POST as $key => $value) {
                $value = self::removeSlashes($value);
                ${$key} = $value;
            }

	     $_contentFile = self::startMvc($_GET,$smarty);
             
             include_once $template;
         }
         
         //makes partial render
         if(self::$controllerObjetc->_pagePartialRender){

            foreach ($_POST as $key => $value) {
                $value = self::removeSlashes($value);

                if(isset($smarty) && (substr_count($_contentFile, '.tpl')>0)){
                    $smarty->assign($key, $value);
                }else{
                    ${$key} = $value;
                }
            }

             $_contentFile = self::startMvc($_GET,$smarty);

             if($_contentFile == ''){
                 SMVC_Start::throwException(SMVC_Locale::getContent(4));
             }
             
             if(isset($smarty) && (substr_count($_contentFile, '.tpl')>0)){
                //if(substr_count($_contentFile, '.tpl')>0) $smarty->display($_contentFile);
             }else{
                 include_once $_contentFile;
             }
             
         }

         
         
    }

}