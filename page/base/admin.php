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
					->addMenuItem($this->api->url('addonpage',array('addon_page'=>'listing\manager')),'Listing')
					->addMenuItem($this->api->url('addonpage',array('addon_page'=>'history\manager')),'History')
					->addMenuItem($this->api->url('addonpage',array('addon_page'=>'emergency\manager')),'Emergency')
					->addMenuItem('logout')
					;
	}
}