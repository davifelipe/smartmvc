<?php

/**
 * automatically load classes
 * @version 1.0
 * @author Davi Felipe <davifelipems@hotmail.com>
 */

include_once 'Start.php';

function __autoload($class_name) {

     
    $dir = SMVC_Start::getPath();

    foreach ($dir as $key => $value) {

        $value = $value.'/';
        $value = str_replace('//', '/', $value);

        $path = str_replace('_', '/', $class_name);

        
        if(file_exists($value.$class_name.'.php')){
           
            include_once $value.$class_name.'.php';
            $loaded=1;
            continue;
            
       
        }else if(file_exists($value.'/'.$class_name.'.class.php')){
            
            include_once $value.'/'.$class_name.'.class.php';
           
            $loaded=1;
            continue;

        }else if(file_exists($value.$path.'.php')){
            
                include_once $value.$path.'.php';
                
                $loaded=1;
                continue;

        }
        
    }
          
        
    if(!$loaded){

        SMVC_Start::throwException(SMVC_Locale::getContent(12).$class_name);

    }
}

?>
