<?php

class AdminController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->_layout()->setLayout('layout');
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        if (!$this->PWDSession->session_data) {
            $this->_redirect('index/login');
        }
        
        
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
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $user_id = $this->PWDSession->session_data['user_id'];
            $this->view->userlist = $userlist = $this->admin->getUserList();
        }
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
    
    public function proctypemanageAction(){
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $this->view->proclist = $this->admin->getProcList();
            $getdata = $this->_request->getQuery();
            $postdata = $this->_request->getPost();

            if ($postdata) {
                $status = $this->admin->createUpdateProcType($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/proctypemanage');
            }

            if ($getdata['id']) {
                $proc_id = $getdata['id'];
                $this->view->procdata = $this->admin->getProcType($proc_id);
            }
        }
    }
    
    
    public function createupdatecontractorAction(){
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $user_id = $this->PWDSession->session_data['user_id'];

            $getdata = $this->_request->getQuery();
            if ($getdata['userid']) {
                $this->view->userid = $getdata['userid'];
                $this->view->userinfo = $this->admin->getContractorInfo($getdata['userid']);
            }

            $postdata = $this->_request->getPost();
            if ($postdata['submit'] == 'submit') {
                //print_r($postdata);exit;
                unset($postdata['submit']);
                $status = $this->admin->createContractor($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/updatestaff');
            }

            if ($postdata['submit'] == 'update') {

                unset($postdata['submit']);
               
                //print_r($postdata);exit;
                $status = $this->admin->updateContractor($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/updatecontractor');
            }
        }
    }
    
    public function updatecontractorAction(){
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $user_id = $this->PWDSession->session_data['user_id'];
            $this->view->userlist = $userlist = $this->admin->getContractorList();
        }
    }
    
    
    
    public function procmethodmanageAction(){
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $this->view->proclist = $this->admin->getProcMethodList();
            $getdata = $this->_request->getQuery();
            $postdata = $this->_request->getPost();

            if ($postdata) {
                $status = $this->admin->createUpdateProcMethod($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/procmethodmanage');
            }

            if ($getdata['id']) {
                $proc_id = $getdata['id'];
                $this->view->procdata = $this->admin->getProcMethod($proc_id);
            }
        }
    }
    
    public function approveauthmanageAction(){
        if ($this->PWDSession->session_data['user_type_id'] == 1) {
            $this->view->userlist = $userlist = $this->admin->getUserTypeList();
            $getdata = $this->_request->getQuery();
            $postdata = $this->_request->getPost();

            if ($postdata) {
                $status = $this->admin->createUpdateUserType($postdata);
                if ($status['o_status_code'] == 1)
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                else
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Admin/approveauthmanage');
            }

            if ($getdata['id']) {
                $proc_id = $getdata['id'];
                $this->view->procdata = $this->admin->getUserType($proc_id);
            }
        }
    }

}
