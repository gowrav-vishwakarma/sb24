<?php
class page_base_site extends Page {
	function init(){
		parent::init();
		$this->setUpMenus();
	}

	function setUpMenus(){
		$this->api->menu
					->addMenuItem('index','Home');
	}
}