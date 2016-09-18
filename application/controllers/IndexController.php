<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout()->setLayout('login_layout');
        $this->PWDSession = new Zend_Session_Namespace('pwd');
        $this->userManage = new Application_Model_UserManage();

        $this->flashMessenger = $this->_helper->FlashMessenger;
        $messages = $this->flashMessenger->getMessages();
        if ($messages) {
            $this->view->messages = $messages;
        }
    }

    public function indexAction()
    {
        //$this->_redirect('Userpanel/index');
        if (isset($this->PWDSession->session_data['user_id'])) {
            $this->_redirect('Userpanel/index');
        }
    }
    
    
    public function loginAction() {
        $postdata = $this->_request->getPost();
        if ($postdata['login'] == 'login') {
            //print_r($postdata);  exit;

            $user_name = $postdata['username'];
            $password = $postdata['password'];

            $user_info = $this->userManage->checkUser($user_name, $password);
//            print_r($user_info);
//            exit;

            if ($user_info[o_status_code]==1) {
                $user_type_id = $user_info['o_user_type_id'];
                $user_name = $user_info['o_user_name'];
                $user_id = $user_info['o_user_id'];
                if($user_type_id==2 or $user_type_id==3 or $user_type_id==4){
                    $fiscal_year = $this->userManage->getFiscalYear();
                }
            } else {
                $this->flashMessenger->addMessage(array('alert-error' => 'INVALID USER! '));
                $this->_redirect('index');
            }
            

            $this->PWDSession->session_data = array(
                "user_name" => $user_name,
                "user_type_id" => $user_type_id,
                "user_id" => $user_id,
                "fiscal_year" => $fiscal_year);


            $this->flashMessenger->addMessage(array('alert-success' => $user_info['o_status_message']));
            $this->_redirect('userpanel/Index');
        } else {
            $this->flashMessenger->addMessage(array('alert-error' => 'Unauthorized Access'));
            $this->_redirect("index");
        }
    }
    
    public function logoutAction() {
        Zend_Session::destroy('true');

        // $cache = new Application_Model_Cache();
        // $cache->clearAllCache();

        $this->_redirect('/index');
    }


}

