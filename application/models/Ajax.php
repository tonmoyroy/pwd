<?php

class Application_Model_Ajax extends Zend_Db_Table
{

public function getBanks(){
    $sql = "SELECT BANK_ID, BANK_NAME, ACTIVE_YN
            FROM BANK
           WHERE ACTIVE_YN = 'Y'";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
}
}

