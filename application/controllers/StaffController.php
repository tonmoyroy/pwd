<?php

class StaffController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout()->setLayout('layout');
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        if (!$this->PWDSession->session_data) {
            $this->_redirect('index/login');
        }
        $this->flashMessenger = $this->_helper->FlashMessenger;
        $this->staff = new Application_Model_Admin();
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

    public function indexAction()
    {
        $user_id = $this->PWDSession->session_data['user_id'];
        $this->view->userinfo = $userinfo = $this->staff->getUserInfo($user_id);
    }


}

