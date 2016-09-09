<?php

class Application_Model_Admin extends Zend_Db_Table {

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
                AND U.USER_ID = $user_id";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }

    public function getUserTypes() {
        $sql = "SELECT USER_TYPE_ID, USER_TYPE_NAME, ACTIVE_YN
                FROM L_USER_TYPES
               WHERE ACTIVE_YN = 'Y'";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function createUser($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_USER(
           :p_user_type,
           :p_user_name,
           :p_full_name,
           :p_email,
           :p_mobile,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    
    public function updateUser($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN UPDATE_USER(
           :p_user_id,
           :p_user_name,
           :p_full_name,
           :p_mobile,
           :p_active_yn,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }

    public function getUserList() {
        $sql = "SELECT U.USER_ID,
                U.USER_NAME,
                U.ACTIVE_YN,
                U.CREATE_DATE,
                U.UPDATE_DATE,
                U.USER_TYPE_ID,
                UT.USER_TYPE_NAME,
                UT.ACTIVE_YN ACTIVE_YN_TYPE,
                UT.APPROVE_AUTH,
                U.MOBILE,
                U.EMAIL,
                U.FULL_NAME
           FROM USERS U, L_USER_TYPES UT
          WHERE U.USER_TYPE_ID = UT.USER_TYPE_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    
    public function getUserTypeList() {
        $sql = "SELECT 
                USER_TYPE_ID, USER_TYPE_NAME, ACTIVE_YN, 
                   APPROVE_AUTH
                FROM L_USER_TYPES ORDER BY USER_TYPE_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    public function getUserType($id){
        $sql = "SELECT USER_TYPE_ID,
                USER_TYPE_NAME,
                ACTIVE_YN,
                APPROVE_AUTH
           FROM L_USER_TYPES
          WHERE USER_TYPE_ID = $id";
        $data = $this->_db->fetchRow($sql);
        return $data;
    }

    public function createUpdateSector($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_OR_UPDATE_SECTOR(
           :p_sector_id,
           :p_sector_name,
           :p_sector_enable,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
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

    public function getSector($sec_id) {
        $sql = "SELECT SECTOR_ID,
                SECTOR_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_SECTOR
          WHERE SECTOR_ID = '$sec_id'";
        $data = $this->_db->fetchRow($sql);
        return $data;
    }

    public function getProcList(){
        $sql = "SELECT PROC_ID,
                PROC_TYPE_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_TYPE ORDER BY PROC_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    public function getProcMethodList(){
        $sql = "SELECT PROC_METHOD_ID,
                PROC_METHOD_NAME,
                ACTIVE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_METHOD ORDER BY PROC_METHOD_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }
    
    public function createUpdateProcType($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_OR_UPDATE_PROC_TYPE(
           :p_proc_id,
           :p_proc_type,
           :p_proc_type_yn,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    
    public function createUpdateUserType($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_OR_UPDATE_USER_TYPE(
           :p_type_id,
           :p_type,
           :p_type_yn,
           :p_auth_yn,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    
    
    
    public function createUpdateProcMethod($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_OR_UPDATE_PROC_METHOD(
           :p_proc_id,
           :p_proc_method,
           :p_proc_method_yn,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    public function getProcType($proc_id){
        $sql = "SELECT PROC_ID,
                PROC_TYPE_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_TYPE
          WHERE PROC_ID = '$proc_id'";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }
    
    public function getProcMethod($proc_id){
        $sql = "SELECT PROC_METHOD_ID,
                PROC_METHOD_NAME,
                ACTIVE_YN,
                CREATE_DATE
           FROM L_PROCUREMENT_METHOD
          WHERE PROC_METHOD_ID = '$proc_id'";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }
    
    
    public function createContractor($data){
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_CONTRACTOR(
           :p_name,
           :p_address,
           :p_email,
           :p_phone,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
    
    public function getContractorList(){
        $sql = "SELECT 
                CONTRACTOR_ID, NAME, ADDRESS, 
                   EMAIL, PHONE, ACTIVE_YN, 
                   CREATE_DATE
                FROM CONTRACTOR";
        $data = $this->_db->fetchAll($sql);
        //print_r($data);exit;
        return $data;
    }
    
    public function getContractorInfo($id){
        $sql = "SELECT CONTRACTOR_ID,
                NAME,
                ADDRESS,
                EMAIL,
                PHONE,
                ACTIVE_YN,
                CREATE_DATE
           FROM CONTRACTOR
          WHERE CONTRACTOR_ID = '$id'";
        $data = $this->_db->fetchRow($sql);
        //print_r($data);exit;
        return $data;
    }
    
    public function updateContractor($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN UPDATE_CONTRACTOR(
           :p_id,
           :p_name,
           :p_address,
           :p_email,
           :p_phone,
           :p_active_yn,
           :o_status_code,
           :o_status_message); END;", $all_data);

        //print_r ($all_data);exit();
        return $all_data;
    }
}
