<?php
/**
 * controllers
 * @version 1.0
 * @author Davi Felipe <davifelipems@hotmail.com>
 */

abstract class SMVC_ControllerAbstract {

    /**Title of page sets it on the action Controller*/
    public $_pageTitle;

    /**page description sets it on the action Controller
     * to recorver this value on the template, use $_pageTitle;
     */
    public $_pageDescription;

     /**page key words sets it on the action Controller
     * to recorver this value on the template, use $_pageKeyWords;
     */
    public $_pageKeyWords;

    /**define if this page will be render
     */
    public $_pageRender = true;

    /**define if this page will be Partialrender
     */
    public $_pagePartialRender = true;

    /**stores the current module*/
    protected $_module;

    /**stores the current controller*/
    protected $_controller;

    /**stores the current action*/
    protected $_action;

    /**stores all of the $_GET and $_post param
     * <Array>
     */
    public $_params;

	public function setModule($module){
		
		if($module == ''){
			$module = 'default';
		}else{
			$module = strtolower($module);
			$module = str_replace('_', '', $module);
		}

		$this->_module = $module;
	}

	public function setController($controller){
		$this->_controller = $controller;
	}

	public function setAction($action){
		$this->_action = $action;
	}

       /**
        * asign variable form controller
        * @access protected
        * @param mixed $key param name assinged to view
        * @param mixed $value value of param
        */
       protected function toView($key, $value){
           if(is_string($value)){
               if(substr_count($value, '"')>0
               || substr_count($value, "'")>0
               ){
                   $value = addslashes($value);
               }
          }

                $_POST[$key]=$value;
        }


}
?>
