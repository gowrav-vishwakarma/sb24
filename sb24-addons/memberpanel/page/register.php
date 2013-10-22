<?php

class page_memberpanel_page_register extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Register Now, Its Free ...')
			->sub('Add your own free listings, get unlimited informations ... ');

		$model=$this->add('Model_Member');
		$model->getElement('password')->system(true);

		$form = $this->add('Form');
		$form->setModel($model,'base');
		$form->addSubmit("Register");

		if($form->isSubmitted()){
			$form->update();
			$form->js(null, $this->js()->univ()->redirect('memberpanel_page_dashboard'))->univ()->closeDialog()->execute();
		}
	}
}