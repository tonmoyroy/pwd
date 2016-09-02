<?php

class AdminController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->_layout()->setLayout('layout');
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        $this->flashMessenger = $this->_helper->FlashMessenger;
        $this->admin = new Application_Model_Admin();
        $this->help = $this->view->addHelperPath('views/helpers');
        $messages = $this->flashMessenger->getMessages();
        if ($messages) {
            $this->view->messages = $messages;
        }


        $user_name = $this->PWDSession->session_data['user_name'];
        Zend_Registry::set('USER_NAME', $user_name);
        $user_type_id = $this->PWDSession->session_data['user_type_id'];
        Zend_Registry::set('USER_TYPE_ID', $user_type_id);
        $user_id = $this->PWDSession->session_data['user_id'];
        Zend_Registry::set('USER_ID', $user_id);
    }

    public function indexAction() {
        Zend_Registry::set('ACTIVE_M_MENU', 'Dashboard');
        Zend_Registry::set('ACTIVE_S_MENU', '');
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->userinfo = $userinfo = $this->admin->getUserInfo($user_id);
    }

    public function createstaffAction() {
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $user_id = $this->PWDSession->session_data['user_id'];
            $this->view->usertypes = $usertypes = $this->admin->getUserTypes();
            $postdata = $this->_request->getPost();
            if ($postdata) {
                //print_r($postdata);exit;
                if (!$postdata['p_user_type']) {
                    $this->flashMessenger->addMessage(array('alert-danger' => "Please Select User Type"));
                    $this->_redirect('Admin/createstaff');
                }
                $status = $this->admin->createUser($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/updatestaff');
            }
        }
    }

    public function updatestaffAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->userlist = $userlist = $this->admin->getUserList();
    }

    public function updatestaffdataAction() {

        $userid = $_GET['userid'];
        //print_r($userid);
        //exit;
        $this->view->userinfo = $userinfo = $this->admin->getUserInfo($user_id);
    }

    public function sectormanageAction() {
        Zend_Registry::set('ACTIVE_M_MENU', 'Dashboard');
        Zend_Registry::set('ACTIVE_S_MENU', 'sector');

        if ($this->PWDSession->session_data['user_type_id'] == 1) {

            $this->view->sectorlist = $this->admin->getSectorList();
            $getdata = $this->_request->getQuery();
            $postdata = $this->_request->getPost();

            unset($postdata['submit']);
//            print_r($postdata);
//            exit;
            if ($postdata['submit']) {
                $status = $this->admin->createsector($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/sectormanage');
            }

            if ($getdata['id']) {
                $sec_id = $getdata['id'];
                $this->view->sectordata = $this->admin->getSector($sec_id);
            }
        }
    }

}
