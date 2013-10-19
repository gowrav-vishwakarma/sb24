<?php

class page_memberpanel_page_register extends page_base_site {
	function init(){
		parent::init();

		$model=$this->add('Model_Member');
		$model->getElement('password')->system(true);

		$form = $this->add('Form');
		$form->setModel($model);
		$form->addSubmit("Register");

		if($form->isSubmitted()){
			$form->update();
			$form->js()->univ()->closeDialog()->execute();
		}
	}
}