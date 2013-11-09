<?php

class page_admin_config extends page_base_admin {
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->setModel($this->api->sb24_config);
		$form->addSubmit("UPDATE");
		if($form->isSubmitted()){
			$form->update();
		}
		
	}
}