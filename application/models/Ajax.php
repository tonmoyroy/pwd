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

    public function getPaymentInfo($ca_no) {
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
          WHERE CA_NO = '$ca_no'
       ORDER BY PAY_DATE";
        $data = $this->_db->fetchRow($sql);
        //echo $sql;exit;
        return $data;
    }

    public function updatefile($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r($all_data);
        
        $this->_db->query("BEGIN NEW_FILE_UPLOAD (
           :p_ca_no,
           :p_file_path,
           :p_file_no,
           :o_status_code,
           :o_status_message); END;", $all_data);
        //var_dump ($all_data);exit;
        return $all_data;
    }
    
    public function showFiles($ca_no){
        $sql = "SELECT FILE_ID,
                CA_NO,
                FILE_PATH,
                FILE_NO,
                C_DATE
           FROM FILE_UPLOAD
          WHERE CA_NO = '$ca_no' ORDER BY FILE_NO";
        $data = $this->_db->fetchAll($sql);
        //echo $sql;exit;
        return $data;
    }

}
