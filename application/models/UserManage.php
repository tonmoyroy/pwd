<?php

class Application_Model_UserManage extends Zend_Db_Table {

    public function checkUser($user_name, $password) {
        $o_user_type_id = sprintf('%20f', '');
        $o_user_name = sprintf('%20f', '');
        $o_user_id = sprintf('%20f', '');
        $o_status_code = sprintf('%20f', '');
        $o_status_message = sprintf('%4000s', '');

        $params = array(
            "p_user_name" => $user_name,
            "p_password" => $password,
            "o_user_type_id" => &$o_user_type_id,
            "o_user_name" => &$o_user_name,
            "o_user_id" => &$o_user_id,
            "o_status_code" => &$o_status_code,
            "o_status_message" => &$o_status_message);
//        var_dump($params);
//        exit;
        $this->_db->query("BEGIN USER_LOGIN(:p_user_name,
               :p_password,
               :o_user_type_id,
               :o_user_name,
               :o_user_id,
               :o_status_code,
               :o_status_message); END;", $params);
//        var_dump($params);
//        exit;
        return $params;
    }

}
