<?php

class Zend_View_Helper_GetRate {

    function GetRate($rate_id, $amount) {
        $this->ajax = new Application_Model_Ajax();

        $str = $this->ajax->getRate($rate_id, $amount);
        
        return $str['AMOUNT'];
    }

 

}