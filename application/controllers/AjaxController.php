<?php

class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
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

    public function indexAction()
    {
        // action body
    }
    
    public function paymentAction(){
        $this->_helper->layout->disableLayout();
        $this->view->pay_method =$pay_meth= $this->_request->getPost('meth');
        $this->view->total =$total= $this->_request->getPost('total');
        $this->view->bank = $this->ajax->getBanks();
        $this->view->paymentinfo = $payinfo = $this->ajax->getPaymentInfo();
       
    }


}

