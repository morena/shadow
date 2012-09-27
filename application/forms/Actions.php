<?php

class Application_Form_Actions extends Zend_Form {

    public function init()
    {
        // set the method for the display form to POST
        $this->setMethod('post');

        $this->setAction('/actions/process');

        $this->addElement('hidden', 'msg');

        $this->addElement('hidden', 'yourname');

        $this->addElement('text', 'name', array(
                    'label' => 'Name of the person you want to call/text',
                    'required' => true,
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        new Zend_Validate_NotEmpty(),
                        new Zend_Validate_Alpha()
                    )
                ))
                ->addElement('text', 'number', array(
                    'label' => 'Number you want to call/txt',
                    'required' => true,
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        new Zend_Validate_NotEmpty(),
                        new Zend_Validate_Digits()
                    )
                ))
                ->addElement('submit', 'call', array(
                    'label' => 'Make a Call'
                ))

                ->addElement('submit', 'text', array(
                    'label' => 'Send a Text'
                ));
    }

}