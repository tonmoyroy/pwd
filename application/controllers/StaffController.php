<?php

class StaffController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->_layout()->setLayout('layout');
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        if (!$this->PWDSession->session_data) {
            $this->_redirect('index/login');
        }
        $this->flashMessenger = $this->_helper->FlashMessenger;
        $this->staff = new Application_Model_Staff();
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
        $fiscal_year = $this->PWDSession->session_data['fiscal_year'];
        Zend_Registry::set('FISCAL_YR', $fiscal_year);
    }

    public function indexAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->userinfo = $userinfo = $this->staff->getUserInfo($user_id);
    }

    public function createcontractagreeAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->sectorlist = $this->staff->getSectorList();
        $this->view->authlist = $this->staff->getAuthorityList();
        $this->view->proclist = $this->staff->getProcList();
        $this->view->procmethlist = $this->staff->getProcMethodList();
        $this->view->conlist = $this->staff->getContractorList();
        $this->view->sdivlist = $this->staff->getSubDivList();


        $postdata = $this->_request->getPost();
        if ($postdata) {
            $status = $this->staff->createContractAgreement($postdata, $user_id);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                $this->_redirect('Staff/showcontractagree?no=' . $postdata['p_ca_no']);
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                $this->_redirect('Staff/createcontractagree');
            }
        }
    }

    public function applistAction() {
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getAppList($getdata['id'],$user_type);
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }
    
    public function fnapplistAction(){
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getfnAppList($getdata['id'],$user_type);
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }

    public function showcontractagreeAction() {
        $this->view->user_type = $this->PWDSession->session_data['user_type_id'];
        $getdata = $this->_request->getQuery();
        $this->view->ca_no = $getdata['no'];
        if($getdata['pay_id']){
            $status = $this->staff->finalizeSecurityPayment($getdata['no'],$getdata['pay_id']);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/showcontractagree?no=' . $getdata['no']);
        }
        $this->view->appdata = $this->staff->getAppData($getdata['no']);
        $this->view->paydata = $this->staff->getPayementMethod();
        $this->view->paymentinfo = $payinfo = $this->staff->getPaymentInfo($getdata['no']);
        $postdata = $this->_request->getPost();
        if ($postdata) {
            $status = $this->staff->createPayment($postdata);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/showcontractagree?no=' . $postdata['p_ca_no']);
        }
    }
    
    public function billcontractagreeAction() {
        $this->view->user_type = $this->PWDSession->session_data['user_type_id'];
        $getdata = $this->_request->getQuery();
        $this->view->ca_no = $getdata['no'];
        $this->view->appdata = $this->staff->getAppData($getdata['no']);
        $this->view->allpayment = $payinfo = $this->staff->getAllPaymentInfo($getdata['no']);
        
    }

}
