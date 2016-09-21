<?php

class Application_Model_Staff extends Zend_Db_Table {

    public function getUserInfo($user_id) {
        $sql = "SELECT U.USER_ID,
                U.USER_NAME,
                U.FULL_NAME,
                U.PASS,
                U.ACTIVE_YN,
                U.DEFAULT_PASSWORD,
                U.CREATE_DATE,
                U.UPDATE_DATE,
                U.USER_TYPE_ID,
                UT.USER_TYPE_NAME,
                U.MOBILE,
                U.EMAIL
           FROM USERS U, L_USER_TYPES UT
          WHERE U.USER_TYPE_ID = UT.USER_TYPE_ID 
                AND U.USER_ID = $user_id
                AND U.ACTIVE_YN = 'Y'";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getSectorList() {
        $sql = "SELECT SECTOR_ID,
                SECTOR_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_SECTOR ORDER BY SECTOR_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function getAuthorityList() {
        $sql = "SELECT USER_TYPE_ID, USER_TYPE_NAME, APPROVE_AUTH
                FROM L_USER_TYPES
               WHERE APPROVE_AUTH = 'Y' ORDER BY USER_TYPE_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function getProcList() {
        $sql = "SELECT PROC_ID,
                PROC_TYPE_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_TYPE ORDER BY PROC_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function getProcMethodList() {
        $sql = "SELECT PROC_METHOD_ID,
                PROC_METHOD_NAME,
                ACTIVE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_METHOD ORDER BY PROC_METHOD_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function getContractorList() {
        $sql = "SELECT 
                CONTRACTOR_ID, NAME, ADDRESS, 
                   EMAIL, PHONE, ACTIVE_YN, 
                   CREATE_DATE
                FROM CONTRACTOR WHERE ACTIVE_YN='Y' ORDER BY 1";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getSubDivList() {
        $sql = "SELECT SUB_DIV_ID, SUB_DIV_NAME, ACTIVE_YN
                FROM L_SUB_DIV
               WHERE ACTIVE_YN = 'Y' ORDER BY 1";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }

    public function createContractAgreement($data, $user_id) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        $user['p_user_id'] = $user_id;
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $user, $out_parms);
        //print_r ($all_data);exit();

        $stmt = new Zend_Db_Statement_Oracle($this->_db, "ALTER SESSION SET NLS_DATE_FORMAT='MM/DD/YYYY'");
        $stmt->execute();

        $this->_db->query("BEGIN CREATE_CONTRACT_AGREEMENT(
           :p_ca_no,
           :p_work_name,
           :p_sector,
           :p_sub_div,
           :p_est_cost,
           :p_auth,
           :p_proc_nature,
           :p_proc_method,
           :p_work_value,
           :p_contractor_id,
           :p_sign_date,
           :p_user_id,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }

    public function getAppList($id,$user_type) {
//        $sql = "SELECT CA.CA_NO,
//                CA.CA_NAME,
//                CA.SECTOR_ID,
//                S.SECTOR_NAME,
//                CA.SUB_DIV_ID,
//                CA.EST_COST,
//                CA.AUTH_ID,
//                CA.PROC_TYPE_ID,
//                CA.PROC_METH_ID,
//                CA.WORK_VALUE,
//                CA.CONTRACTOR_ID,
//                C.NAME,
//                CA.SIGN_DATE,
//                CA.CREATE_DATE
//           FROM CONTRACT_AGREEMENT CA,
//                (SELECT START_DATE, END_DATE
//                   FROM L_FISCAL_YEAR
//                  WHERE FISCAL_YR_ID = $id) FISCAL_YR,
//                L_SECTOR S,
//                CONTRACTOR C
//          WHERE     CA.CREATE_DATE BETWEEN FISCAL_YR.START_DATE AND FISCAL_YR.END_DATE
//                AND CA.SECTOR_ID = S.SECTOR_ID
//                AND CA.CONTRACTOR_ID = C.CONTRACTOR_ID ORDER BY CA.CA_NO";
        
        $sql = "SELECT *
  FROM (  SELECT CA.CA_NO,
                 CA.CA_NAME,
                 CA.SECTOR_ID,
                 S.SECTOR_NAME,
                 CA.SUB_DIV_ID,
                 CA.EST_COST,
                 CA.AUTH_ID,
                 CA.PROC_TYPE_ID,
                 CA.PROC_METH_ID,
                 CA.WORK_VALUE,
                 CA.CONTRACTOR_ID,
                 C.NAME,
                 CA.SIGN_DATE,
                 CA.CREATE_DATE,
                 CURR_STAGE.STAGE,
                 LW.USER_TYPE
            FROM CONTRACT_AGREEMENT CA,
                 (SELECT START_DATE, END_DATE
                    FROM L_FISCAL_YEAR
                   WHERE FISCAL_YR_ID = 1) FISCAL_YR,
                 (  SELECT MIN (STAGE) STAGE, CA_NO
                      FROM WORKFLOW W
                     WHERE STATUS = 'N'
                  GROUP BY CA_NO) CURR_STAGE,
                 L_SECTOR S,
                 CONTRACTOR C,
                 L_WORKFLOW LW
           WHERE     CA.CREATE_DATE BETWEEN FISCAL_YR.START_DATE
                                        AND FISCAL_YR.END_DATE
                 AND CA.SECTOR_ID = S.SECTOR_ID
                 AND CA.CONTRACTOR_ID = C.CONTRACTOR_ID
                 AND CA.CA_NO = CURR_STAGE.CA_NO
                 AND LW.STAGE_ID = CURR_STAGE.STAGE
        ORDER BY CA.CA_NO) TAB
 WHERE TAB.USER_TYPE = $user_type";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }
    
    
    public function getfnAppList($id,$user_type) {
        
        $sql = "SELECT *
  FROM (  SELECT CA.CA_NO,
                 CA.CA_NAME,
                 CA.SECTOR_ID,
                 S.SECTOR_NAME,
                 CA.SUB_DIV_ID,
                 CA.EST_COST,
                 CA.AUTH_ID,
                 CA.PROC_TYPE_ID,
                 CA.PROC_METH_ID,
                 CA.WORK_VALUE,
                 CA.CONTRACTOR_ID,
                 C.NAME,
                 CA.SIGN_DATE,
                 CA.CREATE_DATE,
                 APPROVED.STAGE,
                 LW.USER_TYPE
            FROM CONTRACT_AGREEMENT CA,
                 (SELECT START_DATE, END_DATE
                    FROM L_FISCAL_YEAR
                   WHERE FISCAL_YR_ID = 1) FISCAL_YR,
                 (  SELECT STAGE, CA_NO
    FROM WORKFLOW W
   WHERE STATUS = 'Y') APPROVED,
                 L_SECTOR S,
                 CONTRACTOR C,
                 L_WORKFLOW LW
           WHERE     CA.CREATE_DATE BETWEEN FISCAL_YR.START_DATE
                                        AND FISCAL_YR.END_DATE
                 AND CA.SECTOR_ID = S.SECTOR_ID
                 AND CA.CONTRACTOR_ID = C.CONTRACTOR_ID
                 AND CA.CA_NO = APPROVED.CA_NO
                 AND LW.STAGE_ID = APPROVED.STAGE
        ORDER BY CA.CA_NO) TAB
 WHERE TAB.USER_TYPE = $user_type";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getFiscalYear($id) {
        $sql = "SELECT FISCAL_YR_ID,
                EXTRACT (YEAR FROM START_DATE) START_YEAR,
                EXTRACT (YEAR FROM END_DATE) END_YEAR,
                ACTIVE_YN
           FROM L_FISCAL_YEAR
          WHERE ACTIVE_YN = 'Y' AND FISCAL_YR_ID=$id
       ORDER BY START_YEAR";
        $data = $this->_db->fetchRow($sql);
        return $data;
    }

    public function getAppData($id) {
        $sql = "SELECT CA.CA_NO,
                CA.CA_NAME,
                CA.SECTOR_ID,
                S.SECTOR_NAME,
                CA.SUB_DIV_ID,
                SD.SUB_DIV_NAME,
                CA.EST_COST,
                CA.AUTH_ID,
                UT.USER_TYPE_NAME,
                CA.PROC_TYPE_ID,
                PT.PROC_TYPE_NAME,
                CA.PROC_METH_ID,
                PM.PROC_METHOD_NAME,
                CA.WORK_VALUE,
                CA.CONTRACTOR_ID,
                C.NAME,
                CA.SIGN_DATE,
                CA.CREATE_DATE,
                CA.FORWARD_BY
           FROM CONTRACT_AGREEMENT CA,
                L_SECTOR S,
                CONTRACTOR C,
                L_SUB_DIV SD,
                L_USER_TYPES UT,
                L_PROCUREMENT_TYPE PT,
                L_PROCUREMENT_METHOD PM
          WHERE     CA.SECTOR_ID = S.SECTOR_ID
                AND CA.CONTRACTOR_ID = C.CONTRACTOR_ID
                AND CA.SUB_DIV_ID = SD.SUB_DIV_ID
                AND CA.AUTH_ID = UT.USER_TYPE_ID
                AND CA.PROC_TYPE_ID = PT.PROC_ID
                AND CA.PROC_METH_ID = PM.PROC_METHOD_ID
                AND CA.CA_NO = '$id'
       ORDER BY CA.CA_NO";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getPayementMethod() {
        $sql = "SELECT PAYMENT_METHOD_ID, PAYMENT_METHOD, ACTIVE_YN
                FROM L_PAYMENT_METHOD
               WHERE ACTIVE_YN = 'Y'";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    public function createPayment($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $stmt = new Zend_Db_Statement_Oracle($this->_db, "ALTER SESSION SET NLS_DATE_FORMAT='MM/DD/YYYY'");
        $stmt->execute();

        $this->_db->query("BEGIN CREATE_UPDATE_PAYMENT(
           :p_payment_id,
           :p_ca_no,
           :p_payment_method,
           :p_bank,
           :p_branch,
           :p_payorder,
           :p_date,
           :p_amount,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    public function getPaymentInfo($ca_no) {
        $sql = "SELECT P.PAYMENT_ID,
                P.CA_NO,
                P.PAYMENT_METHOD,
                PM.PAYMENT_METHOD PAYMENT_METHOD_NAME,
                P.BANK_ID,
                B.BANK_NAME,
                P.BRANCH,
                P.PAYORDER_NO,
                P.PAY_DATE,
                P.AMOUNT,
                P.STATUS
           FROM PAYMENT P, BANK B, L_PAYMENT_METHOD PM
          WHERE     CA_NO = '$ca_no'
                AND P.BANK_ID = B.BANK_ID
                AND P.PAYMENT_METHOD = PM.PAYMENT_METHOD_ID
       ORDER BY PAY_DATE";
        //echo $sql;exit;
        $data = $this->_db->fetchRow($sql);
        return $data;
    }
    
    
    public function getAllPaymentInfo($ca_no) {
        $sql = "SELECT P.PAYMENT_ID,
                P.CA_NO,
                P.PAYMENT_METHOD,
                PM.PAYMENT_METHOD PAYMENT_METHOD_NAME,
                P.BANK_ID,
                B.BANK_NAME,
                P.BRANCH,
                P.PAYORDER_NO,
                P.PAY_DATE,
                P.AMOUNT,
                P.STATUS
           FROM PAYMENT P, BANK B, L_PAYMENT_METHOD PM
          WHERE     CA_NO = '$ca_no'
                AND P.BANK_ID = B.BANK_ID
                AND P.PAYMENT_METHOD = PM.PAYMENT_METHOD_ID
       ORDER BY PAY_DATE";
        //echo $sql;exit;
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    public function finalizeSecurityPayment($ca_no,$p_id){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');
        
        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $data['p_payment_id'] =$p_id ;
        $data['p_ca_no'] = $ca_no;
        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN FINALIZE_PAYMENT(
           :p_payment_id,
           :p_ca_no,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }

}
