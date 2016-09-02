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

    public function createupdatestaffAction() {
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $user_id = $this->PWDSession->session_data['user_id'];


            $this->view->usertypes = $usertypes = $this->admin->getUserTypes();

            $getdata = $this->_request->getQuery();
            if ($getdata['userid']) {
                $this->view->userid = $getdata['userid'];
                $this->view->userinfo = $this->admin->getUserInfo($getdata['userid']);
            }

            $postdata = $this->_request->getPost();
            if ($postdata['submit'] == 'submit') {
                //print_r($postdata);exit;
                unset($postdata['submit']);
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

            if ($postdata['submit'] == 'update') {
                
                unset($postdata['submit']);
                unset($postdata['p_email']);
                $status = $this->admin->updateUser($postdata);
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

    public function sectormanageAction() {

        if ($this->PWDSession->session_data['user_type_id'] == 1) {

            $this->view->sectorlist = $this->admin->getSectorList();
            $getdata = $this->_request->getQuery();
            $postdata = $this->_request->getPost();

            if ($postdata) {
                $status = $this->admin->createUpdateSector($postdata);
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
