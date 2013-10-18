<?php
class page_base_admin extends Page {
	function init(){
		parent::init();
		$this->api->auth->check();

		$this->api->template->trySet('page_title','SabKuch 24 :: Admin Panel');

		$this->setUpMenus();
	}

	function setUpMenus(){
		$this->api->menu
					->addMenuItem('admin_index','Dashboard')
					->addMenuItem('admin_master','Masters')
					->addMenuItem('admin_modules_index','Site Modules')
					->addMenuItem('logout')
					;
	}
}