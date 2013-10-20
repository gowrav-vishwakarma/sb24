<?php

class page_memberpanel_page_profile extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->setModel($this->api->auth->model);


	}
}