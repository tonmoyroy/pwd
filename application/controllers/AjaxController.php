<?php

class AjaxController extends Zend_Controller_Action {

    public function init() {
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        if (!$this->PWDSession->session_data) {
            $this->_redirect('index/login');
        }
        $this->flashMessenger = $this->_helper->FlashMessenger;
        $this->ajax = new Application_Model_Ajax();
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
        // action body
    }

    public function paymentAction() {
        $this->_helper->layout->disableLayout();
        $this->view->pay_method = $pay_meth = $this->_request->getPost('meth');
        $this->view->total = $total = $this->_request->getPost('total');
        $this->view->ca_no = $ca_no = $this->_request->getPost('ca_no');
        $this->view->bank = $this->ajax->getBanks();
        $this->view->paymentinfo = $payinfo = $this->ajax->getPaymentInfo($ca_no);
    }

    public function uploadfileAction() {
        $this->_helper->layout->disableLayout();
        $this->view->ca_no = $ca_no = $this->_request->getPost('ca_no'); //print_r($ca_no);
        $this->view->filelist = $file = $this->ajax->showFiles($ca_no); //print_r($file);exit;
    }

    public function storefileAction() {
        $tmp[1] = $_FILES['file1']['tmp_name'];
        $tmp[2] = $_FILES['file2']['tmp_name'];
        $tmp[3] = $_FILES['file3']['tmp_name'];

        $name[1] = $_FILES['file1']['name'];
        $name[2] = $_FILES['file2']['name'];
        $name[3] = $_FILES['file3']['name'];

        $ca_no = $_POST['ca_no'];

        if ($_FILES['file1']['size'] > 5242880 or $_FILES['file2']['size'] > 5242880 or $_FILES['file3']['size'] > 5242880) {
            $errors[] = 'File size must be less than 5 MB';
            echo $namef2;
            exit;
        }

        if (empty($errors) == true) {

            for ($i = 1; $i <= 3; $i++) {
                if ($name[$i]) {
                    $save_name = $ca_no . '_' . $i . '.pdf';
                    $file_path = "documents/pay_security/" . $save_name;
                    if (move_uploaded_file($tmp[$i], $file_path)) {
                        $data['p_ca_no'] = $ca_no;
                        $data['p_file_path'] = 'documents/pay_security/' . $save_name;
                        $data['p_file_no'] = $i;
                        $status = $this->ajax->updatefile($data);                                  //STORE FILENAME IN DATABASE

                        if ($status['o_status_code'] == 1) {
                            
                        } else {
                            unlink($file_path);
                            $status['o_status_code'] = "SORRY!!FILE UPLOAD FAILED";
                        }
                    }
                }
            }
            echo $status['o_status_message'];
            exit;
        } else {
            echo $errors;
            exit;
        }
    }

    public function newbillAction() {
        $this->_helper->layout->disableLayout();
        //$str = $this->baseUrl()."js/dropzone/dropzone.js"; echo $str;exit;
        $this->view->bill_id = $bill_id = $this->_request->getPost('bill_id');
        $this->view->ca_no = $this->_request->getPost('ca_no'); //print_r($ca_no);exit;
        if ($this->_request->getPost('submit')) {
            $postdata = $this->_request->getPost();
            $ca_no = $postdata['ca_no'];
            unset($postdata['submit']);
            unset($postdata['ca_no']);
            $status = $this->ajax->updateNewBill($postdata);
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/divaccbill?no=' . $ca_no);
            //return $status['o_status_code'];
        }
        $this->view->billInfo = $info = $this->ajax->getBillInfo($bill_id); //print_r($info);exit;
    }

    public function finalizebillAction() {
        $this->_helper->layout->disableLayout();
        $this->view->bill_id = $bill_id = $this->_request->getPost('bill_id');
        $this->view->work_value = $work_value = $this->_request->getPost('work_value');
        $this->view->ex_amt =  $this->_request->getPost('amt');
        $this->view->ca_no = $this->_request->getPost('ca_no'); //print_r($ca_no);exit;
        $this->view->rate_chart = $this->ajax->getRateChart();

        if ($this->_request->getPost('submit')) {
            $postdata = $this->_request->getPost();
            
            $ca_no = $postdata['ca_no'];
            unset($postdata['submit']);
            unset($postdata['ca_no']);
            $status = $this->ajax->finalizeBill($postdata);
            
            if ($status['o_status_code'] == 1) {
                $this->flashMessenger->addMessage(array('alert-success' => $status['o_status_message']));
            } else {
                $this->flashMessenger->addMessage(array('alert-danger' => $status['o_status_message']));
            }
            $this->_redirect('Staff/divaccbill?no=' . $ca_no);
            //return $status['o_status_code'];
        }
    }
    
    public function instalmentlistAction(){
        $this->_helper->layout->disableLayout();
        
        $khatid = $this->_request->getPost('khat_id');
        $yearid = $this->_request->getPost('year_id');
        
        $this->view->instalmentlist =$l = $this->ajax->getInstalmentList($khatid,$yearid); //print_r($l);exit;
    }
    
    
    public function restrunningbillAction(){
        $this->_helper->layout->disableLayout();
        
        $bill_id = $this->_request->getPost('bill_id');
        
        $this->view->remaining =$l = $this->ajax->getrestrunningbill($bill_id);
    }


}
