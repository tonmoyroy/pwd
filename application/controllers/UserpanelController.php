<?php

class UserpanelController extends Zend_Controller_Action {

    public function init() {
        $this->admin = new Application_Model_Admin();
        $this->PWDSession = new Zend_Session_Namespace('pwd');

        $user_name = $this->PWDSession->session_data['user_name'];
        Zend_Registry::set('USER_NAME', $user_name);
        $user_type_id = $this->PWDSession->session_data['user_type_id'];
        Zend_Registry::set('USER_TYPE_ID', $user_type_id);
        $user_id = $this->PWDSession->session_data['user_id'];
        Zend_Registry::set('USER_ID', $user_id);
    }

    public function indexAction() {
        $user_type_id = Zend_Registry::get('USER_TYPE_ID');
        //print_r($user_type_id);exit;

        if ($user_type_id == 1)
            $this->_redirect('Admin/Index');
        else if ($user_type_id == 2 or $user_type_id == 3 or $user_type_id == 4) {
            $this->_redirect('Staff/Index');
        } else {
            $this->_redirect('Index/Index');
        }
    }

}
