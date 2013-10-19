<?php

class page_memberpanel_page_base extends page_base_site {
	function init(){
		parent::init();

		$this->api->auth->setModel('Member','username','password');
		$this->api->auth->addHook('updateForm',array($this,'update_login_form'));
		$this->api->auth->check();
		$this->api->menu->addMenuItem('logout');

		$this->setUpMemberMenus();


	}

	function update_login_form($auth){

		$title = $auth->form->add('H2')->set('Login to your account');

		$register_btn=$auth->add('Button')->set('Register Now Free')->addClass('atk-row span12');
		$register_btn->js('click')->univ()->frameURL('Register Your Self',$this->api->url('memberpanel_page_register'));

		$auth->form->add('H4')->set("Don't have account...");
		$auth->form->add($register_btn);
		$auth->form->add('Order')->move($title,'first')->now();

	}

	function setUpMemberMenus(){
		$this->add('Menu')
			->addMenuItem('memberpanel_page_dashboard','Dashboard')
			->addMenuItem('memberpanel_page_listings','My Listings')
			->addMenuItem('memberpanel_page_profile','Profile')
		;
	}

}