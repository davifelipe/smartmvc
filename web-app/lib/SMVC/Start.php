<?php
/**
 * initializes general settings
 * @version 1.1
 * @copyright smartmvc.com.br
 * @author Davi Felipe <davifelipems@hotmail.com>
 */

class SMVC_Start{

      /**
       * stores the modules directory
       * @var <String>
       */
      private static $moduleDir;

      /**
       * store directory of template files
       * @var <String>
       */
      private static $templateDir;

       /**
       * directory stores files compiled templates from Smarty
       * @var <String>
       */
      private static $templateSmartyDirComp;

      /**
       * store directory to be incorporated by autoload
       * @var <Array>
       */
      private static $includePathsDir;

      /**
       * stores configuration parameters of the INI file.
       * @var <Array>
       */
      private static $arrayConfig;

       /**
       * stores smarty object
       * @var <Array>
       */
      private static $smarty;

      /**stores the path separator*/
      private static $pathSeparator;

     /**
      * initializes the configuration variables
      * @return Array containing configuration parameters
      */
     private static function config(){
         
             $s = self::getPathSeparator();

             $root = dirname(__FILE__);

             $root = str_replace("lib{$s}SMVC", '', $root);
                 
            $hostConf = array('localhost'=>'dev',
                             'your-site.com'=>'prod');

            self::$moduleDir = $root."modules{$s}";
            self::$templateDir = $root."layout{$s}";
            self::$templateSmartyDirComp = $root.'templates_c';
            self::$includePathsDir=array(
                                $root."lib{$s}SMARTY{$s}",
                                $root."lib{$s}",
                                $root."models{$s}"
                                );
                                
            $iniFile = $root.'config.ini';


            if(!file_exists($iniFile)){
                self::throwException(SMVC_Locale::getContent(1).$iniFile);
            }
        
            if(!is_dir(self::$moduleDir)){
                self::throwException(SMVC_Locale::getContent(2).self::$moduleDir);
            }

            if(!is_dir(self::$templateDir)){
                self::throwException(SMVC_Locale::getContent(3).self::$templateDir);
            }

            $arrayConfig = parse_ini_file($iniFile,true);

            $configKey = $hostConf[$_SERVER['SERVER_NAME']];

             self::$arrayConfig = $arrayConfig[$configKey];
           
            

     }

     /**
      * include_path makes the templates automatically
      */
     private static function includeTemplatesDir(){

           self::$moduleDir      = self::checkDir(self::$moduleDir);
           self::$templateDir = self::checkDir(self::$templateDir);
           
            $point = opendir (self::$templateDir);
                
                while ($name_itens = readdir ($point)) {
                   
                    if ($name_itens != "." & $name_itens != ".." && $name_itens != "Thumbs.db"){
                        
                        $templateName = str_replace('.php', '', $name_itens);
                        
                        $templateName = str_replace('.phtml', '', $templateName);
                        $templateName = str_replace('.tpl', '', $templateName);

                        
                        self::$includePathsDir[] = self::$moduleDir.$templateName.'/controllers/';
                        self::$includePathsDir[] = self::$moduleDir.$templateName.'/views/';
                        
                    }
                }
     }

     private static function checkDir($dir){
         
          $dir = $dir.'/';
          $dir = str_replace('//', '/', $dir);

          return $dir;
     }

     /**
      * @return object Smarty
      */
     public static function getSmarty(){
		
            if(!isset(self::$smarty)){
                self::$smarty = new Smarty();
            }

			self::$smarty->compile_dir = self::getDirTemplateCompiled();

            return self::$smarty;
     }

     /**
      * @return String module directory
      */
     public static function getAppDir(){
            self::config();
            return self::$moduleDir;
     }

     public static function getPathSeparator(){

           if(isset(self::$pathSeparator)){
               return self::$pathSeparator;
           }

           $version = phpversion();
           $version = substr($version, 0, 3);

          ($version >= 5.2 ? $so = PHP_OS : $so = $_SERVER["HTTP_USER_AGENT"]);
          $so = strtolower($so);

            if(substr_count($so,'win')>0){
                self::$pathSeparator= "\\";
            }else{
		self::$pathSeparator = "/";
            }

            return self::$pathSeparator;
     }

     /**
      * Retrieves array of paths to be added.
      * The paths must be as a reference, the index.php file in the folder public_html
      * @return Array containing directories to be included
      * OBS: When creating new modules include the directory of the controller and view the new module
      */
     public static function getPath(){
         self::config();
         self::includeTemplatesDir();

         return self::$includePathsDir;
     }

    

     /**
      * returns the directory where all the templates
      * @return String templates directory
      */
     public static function getDirTemplate(){
         self::config();
         return self::$templateDir;
     }

      /**
      * returns the directory where all the templates
      * @return String directory templates
      */
     public static function getDirTemplateCompiled(){
         self::config();
         return self::$templateSmartyDirComp;
     }

    
     public static function getShowErros(){
         self::config();

         return self::$arrayConfig['show_error'];
     }

     public static function getGenericMsgErro(){
         self::config();

         return self::$arrayConfig['generic_msg_error'];
     }

     public static function getDbName(){
          self::config();

        return self::$arrayConfig['db_name'];
     }

     public static function getDbServer(){
          self::config();
         return self::$arrayConfig['db_server'];
     }

     public static function getDbUser(){
          self::config();
         return self::$arrayConfig['db_user'];
     }

     public static function  getDbPass(){
        self::config();
         return self::$arrayConfig['db_pass'];
     }

     public static function getActionParam(){
         self::config();
         return self::$arrayConfig['action_param'];
     }

     public static function getNamePtBRDateFieldFormat(){
         self::config();
         return self::$arrayConfig['name_ptBR_date_field_format'];
     }

     public static function getNamePtBRFloatFieldFormat(){
         self::config();
         return self::$arrayConfig['name_ptBR_float_field_format'];
     }

     public static function getPageTitle(){
         self::config();
         return self::$arrayConfig['page_title'];
     }

     public static function getPageKeyWords(){
         self::config();
         return self::$arrayConfig['page_keywords'];
     }
    
     public static function getBaseUrl(){
         self::config();
         return self::$arrayConfig['base_url'];
     }


     /**
      * show error according to the configuration
      * @param String $msg error messenger
      */
     public static function throwException($msg){

            if(self::getShowErros()){
                throw new SMVC_Exception_Start($msg);
            }else{
                throw new SMVC_Exception_Start(self::getGenericMsgErro());
            }


     }
   
}

