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
                AND U.USER_ID = $user_id
                AND U.ACTIVE_YN = 'Y'";
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

    public function getUserList() {
        $sql = "SELECT U.USER_ID,
                U.USER_NAME,
                U.ACTIVE_YN,
                U.CREATE_DATE,
                U.UPDATE_DATE,
                U.USER_TYPE_ID,
                UT.USER_TYPE_NAME,
                U.MOBILE,
                U.EMAIL,
                U.FULL_NAME
           FROM USERS U, L_USER_TYPES UT
          WHERE U.USER_TYPE_ID = UT.USER_TYPE_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function createSector($data) {
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $out_parms = array(
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message
        );

        $all_data = array_merge($data, $out_parms);
        //print_r ($all_data);exit();

        $this->_db->query("BEGIN CREATE_SECTOR(
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
           FROM L_SECTOR
          WHERE ENABLE_YN = 'Y' ORDER BY SECTOR_ID";
        $data = $this->_db->fetchAll($sql);
        return $data;
    }

    public function updateSector($sec_id,$sec_name,$enable) {
        $this->_db->query("UPDATE L_SECTOR SET SECTOR_ID = SECTOR_ID,SECTOR_NAME = UPPER ('$sec_name'),ENABLE_YN = '$enable',CREATE_DATE = SYSDATE WHERE SECTOR_ID = $sec_id");
        return 1;
    }
    
    public function getSector($sec_id){
        $sql = "SELECT SECTOR_ID,
                SECTOR_NAME,
                ENABLE_YN,
                CREATE_DATE
           FROM L_SECTOR
          WHERE SECTOR_ID = '$sec_id'";
        $data = $this->_db->fetchRow($sql);
        return $data;
    }

}
