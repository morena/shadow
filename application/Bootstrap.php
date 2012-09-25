<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->setEncoding('UTF-8');
    }

    protected function _initPlaceholders()
    {
        $this->view->headTitle('Shadow')
                ->setSeparator(' - ');
    }

    protected function _initStylesheets()
    {
        $this->view->headLink()->appendStylesheet('/sys/styles/reset.css');
        $this->view->headLink()->appendStylesheet('/sys/styles/style.css');
    }

    /*protected function _initJavascripts()
    {
        $this->view->headScript()->appendFile('/sys/js/prototype.js');
    }*/
}