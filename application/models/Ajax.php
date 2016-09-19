<?php

class Application_Model_Ajax extends Zend_Db_Table {

    public function getBanks() {
        $sql = "SELECT BANK_ID, BANK_NAME, ACTIVE_YN
            FROM BANK
           WHERE ACTIVE_YN = 'Y'";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getPaymentInfo() {
        $sql = "SELECT PAYMENT_ID,
                CA_NO,
                PAYMENT_METHOD,
                BANK_ID,
                BRANCH,
                PAYORDER_NO,
                TO_CHAR (PAY_DATE, 'MM/DD/YYYY') PAY_DATE,
                AMOUNT,
                STATUS
           FROM PAYMENT
          WHERE CA_NO = 'CA-111000'
       ORDER BY PAY_DATE";
        $data = $this->_db->fetchRow($sql);
        return $data;
    }

}
