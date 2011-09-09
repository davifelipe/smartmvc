<?php

class SMVC_Exception_Start extends Exception{

    public function __construct($message,$code = 45) {
        
        parent::__construct($message,$code);
    }
    
}
