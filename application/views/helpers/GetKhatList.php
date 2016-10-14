<?php

class Zend_View_Helper_GetKhatList {

    function GetKhatList() {
        $this->ajax = new Application_Model_Ajax();

        $str = $this->ajax->getKhatList();
        
        return $str;
    }

 

}