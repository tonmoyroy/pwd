<?php

class Zend_View_Helper_GetFiscalYearList{

    function GetFiscalYearList() {
        $this->staff = new Application_Model_Staff();

        $str = $this->staff->getFiscalYearList();
        
        return $str;
    }

 

}