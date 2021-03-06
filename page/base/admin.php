<?php
class page_base_admin extends Page {
	function init(){
		parent::init();
		$this->api->auth->setModel('Staff','username','password');

		$this->api->jquery->addStaticInclude('elrte/js/elrte.min');
		$this->api->jquery->addStaticStyleSheet('elrte/css/elrte.min');
		$this->api->jquery->addStaticInclude('tinymce/jquery.tinymce.min');

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->tryDel('shrink');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('body_margin');

		$this->api->auth->check();

		$this->api->template->trySet('page_title','SabKuch 24 :: Admin Panel');
		$this->api->template->trySet('page',$this->api->url('base_admin'));

		$this->setUpAdminMenus();
	}

	function setUpAdminMenus(){
		$this->api->menu
					->addMenuItem('admin_index','Dashboard')
					->addMenuItem('admin_master','Masters')
					->addMenuItem('admin_modules_index','Site Modules')
					->addMenuItem('adds_page_admin_index','Adds management')
					->addMenuItem('admin_config','Change Your Front Image')
					->addMenuItem('logout')
					;
	}

	
}