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
        $this->view->ca_no = $ca_no = $this->_request->getPost('ca_no');//print_r($ca_no);
        $this->view->filelist =$file= $this->ajax->showFiles($ca_no);//print_r($file);exit;

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
   

}
