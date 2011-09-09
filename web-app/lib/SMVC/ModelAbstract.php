<?php
/**
 * All entities must extend this class
  * Implements methods to mySql.
  * To use entities to use other database,
  * You must change the methods mysql_ calls to the database you want
  * use
 * @version 1.0
 * @author Davi Felipe <davifelipems@hotmail.com>
 */

abstract class SMVC_ModelAbstract{
    /**
     * table name
     * @name $_table
     */
    protected $_table;

    /**
     * array that stores the field names and values ​​of
      * entity
     * @name $_data
     */
    protected $_data;

    public $debug;

    protected $_view;

    private function connect(){
     
         $error['connect'] = SMVC_Locale::getContent(7);
         $error['select']  = SMVC_Locale::getContent(8);

         $con=mysql_pconnect(SMVC_Start::getDbServer(),SMVC_Start::getDbUser(),SMVC_Start::getDbPass())
         or SMVC_Start::throwException($error['connect']);

        mysql_select_DB(SMVC_Start::getDbName()) or SMVC_Start::throwException($error['select']);

        if(!$con){
            SMVC_Start::throwException($error['connect']);
        }
    }

    /**
     * @param boolean $debug true or false
     */
    protected function setDebug($debug){
        $this->debug = $debug;
    }


    protected function setTable($table){
        $this->_table = $table;
    }

    public function getTable(){

        return $this->_table;
    }

     /**
     * test date format
     * @param String date to be tested
     * @return int 1 EN for format 0 to format or returns null for Br
      * Invalid format
     */
     private function typeDateFormat($date){
         if(substr_count($date, '-')>0
         && !substr_count($date, '/')>0
         ){
              
              return 1;
         }else if(!substr_count($date, '-')>0
               && substr_count($date, '/')>0
         ){
            
             return 0;
         }else{
             return null;
         }

     }

    /**
     * method to convert a date or date and time format (English) en-EN for (Portuguese Brazil) pt-BR
     * @param String $ date Date in the format en-EN
     * @return Returns date in the format en_US
     */
    private function dateFormatToPt($date){
            
          
           if(!$this->typeDateFormat($date)){
                return $date;
           }else if(!is_string($date)){
                return null;
           }

         

            $arrayDate = explode('-', $date);

           
            $arrayDate[2] = substr($arrayDate[2], 0, 2);

            
            $date = trim($date);

           
            if($date == ''){
                $arrayDate[0]='0000';
                $arrayDate[1]='00';
                $arrayDate[2]='00';
            }
         
            $hours = substr($date, 10, 10);

            if(strlen($hours > 7)){
                return $arrayDate[2]."/".$arrayDate[1]."/".$arrayDate[0]." ".$hours;
            }else{
                return $arrayDate[2]."/".$arrayDate[1]."/".$arrayDate[0];
            }


    }

     /**
     * method to convert a date or date and time format (Portuguese Brazil) pt-BR (English) en-EN
     * @param String $date Date format in en
     * @return Returns date in the format en_EN
     */
    public function dateFormatToEn($date){

           
           if($this->typeDateFormat($date)){
                return $date;
           }else if(!is_string($date)){
                return null;
           }

            $arrayDate = explode('/', $date);

            
            $arrayDate[2] = substr($arrayDate[2], 0, 4);

            
            $date = trim($date);

            
            if($date == ''){
                $arrayDate[0]='00';
                $arrayDate[1]='00';
                $arrayDate[2]='0000';
            }

            
            $hours = substr($date, 10, 10);
            
            if(strlen($hours > 7)){
                $out = $arrayDate[2]."-".$arrayDate[1]."-".$arrayDate[0]." ".$hours;
            }else{
                $out = $arrayDate[2]."-".$arrayDate[1]."-".$arrayDate[0];
            }

            $out = str_replace("'", '', $out);

            
            return $out;

    }

    /**
     * method to convert float number to EN EN
     * replacing (.) por (,)
     * @param mixed number or string (.)
     * @return number returns to (,) instead of (.)
     */
     public function floatToPT($float){

         $result = number_format($float,2,",",".");
         return $result;

     }

     /**
     * method to convert float number to EN
     * replacing (,) por (.)
     * @param mixed number or string (,)
     * @return number returns to (.) instead of (,)
     */
     public function floatToEN($float){
         $result= str_replace(',', '.', $float);
         return $result;

     }

     /**
      * format date or float Pt_BR Field
      * @param String $defaultNameField part name or complete name
      *  of date or float field should be 1 or many. ex: dta OR date, data
      * @param String $fieldName name of field
      * @param array $line array with resultset form data base
      * @param String $type 'date' or 'float'
      * @return returns formated line of resultset
      */
     private function formatField($defaultNameField,$fieldName,$fieldValue,$line,$type){

         if(substr_count($defaultNameField, ',')>0){

           $aDefaultField = explode(',', $defaultNameField);

           foreach ($aDefaultField as $defaultItem) {

                $defaultItem = trim($defaultItem);

                if(substr_count($fieldName,$defaultItem)>0){

                    switch ($type) {
                        case 'date':
                            $line[$fieldName] = $this->dateFormatToPt($fieldValue);
                            break;

                        case 'float':
                            $line[$fieldName] = $this->floatToPT($fieldValue);

                            break;

                    }
                        

                }
           }

       }else if(substr_count($fieldName,$defaultNameField)>0){

                switch ($type) {
                        case 'date':
                            $line[$fieldName] = $this->dateFormatToPt($fieldValue);
                            break;

                        case 'float':
                            $line[$fieldName] = $this->floatToPT($fieldValue);

                            break;
                }

       }

       return $line;

     }

     /**
      * automatically formats the field lines
      */
     private function formatLineToPtBR($line){

		 if($_SESSION['_currentLocale'] != 'pt_BR'){
			return $line;
		 }

         if(!is_array($line)){
            return null;
         }

        foreach ($line as $fieldName => $value) {

               $dateFormatBr = SMVC_Start::getNamePtBRDateFieldFormat();

               $floatFormatBr = SMVC_Start::getNamePtBRFloatFieldFormat();

               $line = $this->formatField($dateFormatBr, $fieldName, $value,$line, 'date');

               $line = $this->formatField($floatFormatBr, $fieldName, $value, $line, 'float');
          }

          return $line;
    }

    /**
     * Were riding clause query 
     * @access private
     */
    private function montWhere($where){
        
        if(!isset($where)){
            return $where;
        }

        if(is_string($where)){
                $where1=$where;
        }else if(is_array($where)){
                $where1=implode(' AND ', $where);
        }

        if(is_string($where)){
            
            if($where == ''                    
            || substr_count($where, 'WHERE')>0 
            ){
                $where2 = $where;
              }else{
                $where2= 'WHERE '.$where1;
              }
        }else{
            $where2= 'WHERE '.$where1;
        }
        

         return $where2;
    }

    /**
     * put double quotes string if not already in quotes
     * @access private
     */
    private function putSlashesIfNot($string){
        $tallLess = strlen($string)-1;
        $first = substr($string, 0, 1);
        $penultimate = strlen($string)-1;
        $last = substr($string, $penultimate, 1);

       
        if(($first != '"' || $first != "'")
           &&
           ($last != '"' || $last != "'")
           ){
                
                $string = addslashes($string);
                
                $string="'".$string."'";
                
           }else{

              
               $half =substr($string, 1, $tallLess);

               
               $half = addslashes($half);

              
               $string = "'".$half."'";
           }

           return $string;
    }

 


    /**
     * receiving updates from array data
     * @param [opcional]array $date array of data that will be updated example: array ('fieldName' => 'value');
     * @param Array|String $where string or array with where clause example: 'id = 1'
     */
    public function update($where,array $date = null){

        if(is_array($date)){
               $this->dataSync($date);
        }

         $this->connect();

         if(!is_array($this->_data)){
               return false;
        }

       
        $where = $this->montWhere($where);

        foreach ($this->_data as $key => $value) {

            if(substr_count($key, SMVC_Start::getNamePtBRDateFieldFormat())>0){
                $value = $this->dateFormatToEn($value);
            }

            $value = $this->valueFilters($value);

            $dados[]="`".$key."` = ".$value;
           
        }
        
        $dados = implode(', ', $dados);
 
        $query='UPDATE `'.$this->_table.'`
                  SET
                     '.$dados.' '
                  .$where;
                 
         if($this->debug){
            die($query);
        }

           
        if(SMVC_Start::getShowErros()){
             mysql_query($query) OR die($query);
         }else{
             mysql_query($query) OR die(SMVC_Start::getMsgErroGenerico());
         }

        $result= mysql_affected_rows();


        return $result;
    }

    /**
     * values ​​that it will be stored in the table
     * @param Mixed $value Value to be written
     * @return Mixed value treaty
     */
     private function valueFilters($value){

         
          if(is_string($value)){
                
                $value = $this->putSlashesIfNot($value);
          }
          
          return $value;
     }

     protected function dataSync(array $date){
         if(is_array($date)){

            foreach ($date as $key => $value) {
                     if(!is_array($value)){
                         $this->_data[$key] = $value;
                     }
            }

        }
     }

    /**
     * insert array is receiving data
     * @param [opcional]array $date array of data that will be updated example: array ('fieldName' => 'value');
     */
    public function insert(array $date = null){

        if(is_array($date)){
               $this->dataSync($date);
        }

        $this->connect();

        if(!is_array($this->_data)){
               return false;
        }
        
        foreach ($this->_data as $key => $value) {
            
            if(substr_count($key, SMVC_Start::getNamePtBRDateFieldFormat())>0){
                $value = $this->dateFormatToEn($value);
            }

            $value = $this->valueFilters($value);

            $fields[]=$key;
            $values[]=$value;
        }

       
            $fields = implode(', ', $fields);
            $values = implode(', ', $values);
        

        $query ='INSERT INTO `'.$this->_table.'`
                             ('.$fields.')
                      VALUES
                             ('.$values.');';

       

         $res = mysql_query($query) OR SMVC_Start::throwException(SMVC_Locale::getContent(9).$query.mysql_error());

        $result= mysql_affected_rows();



        return $result;
    }

    /**
     * do delete
     * @param Array|String $where string or array with where clause example: 'id = 1'
     */
    public function delete($where){

         $this->connect();

         $where = $this->montWhere($where);

         $query = 'DELETE FROM '.$this->_table.' '.$where;

          if($this->debug){
            die($query);
          }
        
          mysql_query($query) OR SMVC_Start::throwException(SMVC_Locale::getContent(9).$query.mysql_error());
          $result= mysql_affected_rows();


        return $result;
    }

     /**
     * This method should be used in case of more complex queries
     * @param String $query Native sql query
     * @return Array array with the outcome of the consultation
     */
     public function select($query){

        $this->connect();
        

         if($this->debug){
            die($query);
         }

         $res = mysql_query($query) OR SMVC_Start::throwException(SMVC_Locale::getContent(9).$query.mysql_error());

         while ($result = mysql_fetch_array($res)) {
             $date[]=$result;
         }

        foreach ($date as $key => $line) {

             $line = $this->formatLineToPtBR($line);

              $date[$key] = $line;
         }

         return $date;
     }

     /**
      * test, validate and limit returns
      * @param String limit clause
      * @return String limit clause validated
      */
      private function montLimit($limit){
          
          $limit = trim($limit);

          if($limit != ''){

          if(substr_count($limit, "LIMIT")>0 
          &&!substr_count($limit, ",")>0     
          ){
              throw new SMVC_Exception_Start(SMVC_Locale::getContent(10).$limit);
          }
                return 'LIMIT '.$limit;
          }else{
              
              return $limit;
          }
      }
      
      /**
      * test, validate and order returns
      * @param String order clause
      * @return String order clause validated
      */
      private function montOrder($order){

          $order = trim($order);

          if($order != ''){

              if(!substr_count($order, "ASC")>0  
              && !substr_count($order, "DESC")>0 
              && !substr_count($order, "desc")>0 
              && !substr_count($order, "asc")>0 
              ){
                    throw new SMVC_Exception_Start(SMVC_Locale::getContent(11).$limit);
              }
                    return 'ORDER BY '.$order;
          }else{
              
              return $order;
          }

          
      }

     /**
      * returns array with result of query
      * @param Array|String $where string ro array with condition ex: 'id=1'
      * @param String[opcional] $order order field ex: 'campo ASC'
      * @param String[opcional] $limit limit ex: '1, 2'
      * @return Array array of the resultset
      */
     public function fetchAll($where = null, $order =null , $limit = null){
         
         $this->connect();

         $where = $this->montWhere($where);

         $order = $this->montOrder($order);
         $limit = $this->montLimit($limit);

         $query = 'SELECT * FROM '.($this->_view != '' ? $this->_view : $this->_table).' '.$where.' '.$order.' '.$limit;

          if($this->debug){
                die($query);
          }
         
         $res = mysql_query($query) OR SMVC_Start::throwException(SMVC_Locale::getContent(9).$query.mysql_error());

         while ($result = mysql_fetch_array($res)) {
             $date[]=$result;
         }

         if(!is_array($date)) $date = array();

         foreach ($date as $key => $line) {

             $line = $this->formatLineToPtBR($line);

              $date[$key] = $line;
         }

         return $date;
     }

      

    /**
      * returns array with result of query
      * @param Array|String $where string ro array with condition ex: 'id=1'
      * @param String[opcional] $order order field ex: 'campo ASC'
      * @param String[opcional] $limit limit ex: '1, 2'
      * @return Array array of the resultset
      */
    public function fetchRow($where , $order =null , $limit = null){

         $this->connect();

         $where = $this->montWhere($where);

         $order = $this->montOrder($order);
         $limit = $this->montLimit($limit);

         $query = 'SELECT * FROM '.($this->_view != '' ? $this->_view : $this->_table).' '.$where.' '.$order.' '.$limit;


          if($this->debug){
                die($query);
            }
         
         $res = mysql_query($query) OR SMVC_Start::throwException(SMVC_Locale::getContent(9).$query.mysql_error());

        

        $date = mysql_fetch_assoc($res);

        $date = $this->formatLineToPtBR($date);

        if(!is_array($date)) $date = array();
         
         return $date;
     }

}
