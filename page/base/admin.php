<?php
class page_base_admin extends Page {
	function init(){
		parent::init();
		$this->api->auth->check();

		$this->setUpMenus();
	}

	function setUpMenus(){
		$this->api->menu
					->addMenuItem('admin_index','Dashboard')
					->addMenuItem('admin_master','Masters')
					->addMenuItem('directory_manager','Directory')
					->addMenuItem('logout')
					;
	}
}