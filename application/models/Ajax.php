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
                TO_CHAR (ISSUE_DATE, 'MM/DD/YYYY') ISSUE_DATE,
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
    
    public function updateNewBill($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r($all_data);
        $stmt = new Zend_Db_Statement_Oracle($this->_db, "ALTER SESSION SET NLS_DATE_FORMAT='MM/DD/YYYY'");
        $stmt->execute();
        
        $this->_db->query("BEGIN UPDATE_NEW_BILL (
           :p_bill_id,
           :p_mb_no,
           :p_mb_pg_no,
           :p_date,
           :p_cheque_no,
           :p_voucher_no,
           :o_status_code,
           :o_status_message); END;", $all_data);
        //var_dump ($all_data);exit;
        return $all_data;
    }
    
    public function getBillInfo($bill_id){
        $sql = "SELECT BILL_ID,
                CA_NO,
                AMOUNT,
                MB_NO,
                MB_PG_NO,
                TO_CHAR (MB_DATE, 'MM/DD/YYYY') MB_DATE,
                RETENATION,
                VAT,
                IT,
                STATUS,
                BILL_PASS_DATE,
                CHEQUE_NO,
                VOUCHER_NO
           FROM BILL
          WHERE BILL_ID = $bill_id";
        $data = $this->_db->fetchRow($sql);
        //echo $sql;exit;
        return $data;
    }
    
    public function getRateChart(){
        $sql = "SELECT RC.RATE_ID,
                RC.RATE_TYPE,
                RT.RATE_TYPE_NAME,
                RC.PERCENT,
                RC.RANGE_LOW,
                RC.RANGE_HIGH
           FROM RATE_CHART RC, L_RATE_TYPES RT
          WHERE RC.RATE_TYPE = RT.RATE_TYPE_ID AND RT.ACTIVE_YN = 'Y'
       ORDER BY RC.RATE_ID ASC";
        $data = $this->_db->fetchAll($sql);
        //echo $sql;exit;
        return $data;
    }
    
    public function finalizeBill($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r($all_data);
        
        $this->_db->query("BEGIN FINALIZE_NEW_BILL (
           :p_bill_id,
           :p_amount,
           :p_retention,
           :p_vat,
           :p_it,
           :o_status_code,
           :o_status_message); END;", $all_data);
        //var_dump ($all_data);exit;
        return $all_data;
    }
    
    public function getKhatList(){
        $sql = "SELECT KHAT_ID, KHAT_NAME, STATUS
                FROM KHATS
               WHERE STATUS = 'Y'";
        $data = $this->_db->fetchAll($sql);
        //echo $sql;exit;
        return $data; 
    }
    
    public function getInstalmentList($khat_id,$yr_id){
        $sql = "SELECT INSTALMENT_ID, AMOUNT || ' [VALID-' || VALID_DATE || ']' AMOUNT
                FROM INSTALMENT
               WHERE KHAT_ID = $khat_id AND FISCAL_YR_ID = $yr_id AND STATUS = 'N'";
        $data = $this->_db->fetchAll($sql);
        //echo $sql;exit;
        return $data; 
    }
    
    public function getrestrunningbill($bill_id){
        $sql = "SELECT X.AMOUNT - Y.AMOUNT REMAINING
                FROM (SELECT NVL (RB.AMOUNT, 0) AMOUNT
                        FROM RUNNING_BILL RB
                       WHERE RB.RN_BILL_ID = $bill_id) X,
                     (SELECT NVL (SUM (AMOUNT), 0) AMOUNT
                        FROM BILL
                       WHERE RN_BILL_ID = $bill_id) Y";
        $data = $this->_db->fetchRow($sql);
        //echo $sql;exit;
        return $data; 
    }
}
