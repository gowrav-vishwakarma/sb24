<?php

class page_logout extends page_memberpanel_page_base {
	function init(){
		parent::init();
		$this->api->auth->logout();
		$this->api->redirect('index');
	}
}