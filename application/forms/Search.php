<?php

class Application_Form_Search extends Zend_Form
{
	public function init()
	{
		// set the method for the display form to POST
		$this->setMethod('post');

		$this->addElement('text', 'location', array(
			'label'      => 'Insert a location (i.e. Chelsea or SW4):',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
			)
		))
		->addElement('submit', 'submit', array(
			'label' => 'Price It!'
		));
	}
}