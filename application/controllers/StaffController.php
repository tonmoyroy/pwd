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

        $this->view->fundinfo = $this->staff->getFundStatus();
    }

    public function createcontractagreeAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        if ($user_type == 3) {
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
    }

    public function applistAction() {
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getAppList($getdata['id'], $user_type, 'N');
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }

    public function fnapplistAction() {
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getfnAppList($getdata['id'], $user_type);
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }

    public function approvlistAction() {
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getAppList($getdata['id'], $user_type, 'Y');
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }

    public function exapplistAction() {
        $getdata = $this->_request->getQuery();
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        $this->view->applist = $this->staff->getExecutiveAppList($getdata['id'], $user_type);
        $this->view->year = $this->staff->getFiscalYear($getdata['id']);
    }

    public function showcontractagreeAction() {
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        if ($user_type == 3) {
            $getdata = $this->_request->getQuery();
            $this->view->ca_no = $getdata['no'];

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
    }

    public function billcontractagreeAction() {
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        if ($user_type == 2) {
            $getdata = $this->_request->getQuery();
            if ($getdata) {
                $this->view->ca_no = $getdata['no'];
                $this->view->appdata = $appdata = $this->staff->getAppData($getdata['no']); //print_r($appdata);exit;
                $this->view->paymentinfo = $payinfo = $this->staff->getPaymentInfo($getdata['no']);
                $this->view->allpayment = $payinfo = $this->staff->getBillPayment($getdata['no']);
                $this->view->runningBill = $this->staff->getRunningBillInfo($appdata['SIGN_DATE'], $appdata['KHAT_ID']);
                $this->view->totalBudget = $this->staff->getTotalBudget($appdata['SIGN_DATE']);
                $total_amt = 0;
                foreach ($payinfo as $pay) {
                    $total_amt = $total_amt + $pay['AMOUNT'];
                }
                $this->view->total_amt = $total_amt;
            }

            $postdata = $this->_request->getPost();
            if ($postdata) {
                //print_r($postdata);exit;
                if (!$postdata['p_amount']) {
                    $this->flashMessenger->addMessage(array('alert-danger' => "SORRY!! REQUIRED VALUE CANNOT BE NULL"));
                    $this->_redirect('Staff/billcontractagree?no=' . $postdata['p_ca_no']);
                }
                $status = $this->staff->createNewBill($postdata);
                if ($status['o_status_code'] == 1) {
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                } else {
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                }
                $this->_redirect('Staff/billcontractagree?no=' . $postdata['p_ca_no']);
            }
        }
    }

    public function divaccbillAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        if ($user_type == 4) {
            $getdata = $this->_request->getQuery();
            $this->view->ca_no = $getdata['no'];

            if ($getdata['pay_id']) {
                $status = $this->staff->finalizeSecurityPayment($getdata['no'], $getdata['pay_id'], $user_id);
                if ($status['o_status_code'] == 1) {
                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
                } else {
                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
                }
                $this->_redirect('Staff/divaccbill?no=' . $getdata['no']);
            }

            $this->view->appdata = $this->staff->getAppData($getdata['no']);
            $this->view->paymentinfo = $payinfo = $this->staff->getPaymentInfo($getdata['no']);
            $this->view->allpayment = $payinfo = $this->staff->getBillPayment($getdata['no']);

//            $postdata = $this->_request->getPost();
//            if ($postdata) {
//                print_r($postdata);
//                exit;
//
//                if ($status['o_status_code'] == 1) {
//                    $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
//                } else {
//                    $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
//                }
//                $this->_redirect('Staff/billcontractagree?no=' . $postdata['p_ca_no']);
//            }
        }
    }

    public function divaccpaymentAction() {
        $this->view->user_type = $user_type = $this->PWDSession->session_data['user_type_id'];
        if ($user_type == 4) {
            $getdata = $this->_request->getQuery();
            $this->view->ca_no = $getdata['no'];

            $this->view->appdata = $this->staff->getAppData($getdata['no']);
            $this->view->paymentinfo = $payinfo = $this->staff->getPaymentInfo($getdata['no']);
        }
    }

    public function showcontractorAction() {
        $getdata = $this->_request->getQuery();
        $this->view->cid = $cid = $getdata['no'];
        $this->view->c_info = $this->staff->getContractorInfo($cid);
        $this->view->contract_list = $this->staff->getContractAwardedInfo($cid);
    }

    public function instalmentAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->helper = $this->help;
        $this->view->instalmentlist = $this->staff->getInstalmentList();
        $postdata = $this->_request->getPost();
        if ($postdata) {
//            print_r($postdata);exit;
            $status = $this->staff->createInstalment($postdata);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/instalment');
        }
    }

    public function runningbillAction() {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->helper = $this->help;
        $this->view->instalmentlist = $this->staff->getRunningBillList();
        $postdata = $this->_request->getPost();
        if ($postdata) {
            //print_r($postdata);exit;
            $status = $this->staff->createRunningBill($postdata);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/runningbill');
        }
    }

}
