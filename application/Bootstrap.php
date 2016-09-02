<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initViewHelpers() {
        $view = new Zend_View();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Application_View_Helper_');
        $viewRenderer->setView($view);


        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        @date_default_timezone_set('Asia/Dacca');
    }

}
