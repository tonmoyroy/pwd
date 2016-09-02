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

}
