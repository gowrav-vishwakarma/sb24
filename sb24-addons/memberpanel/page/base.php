<?php

class page_memberpanel_page_base extends page_base_site {
	function init(){
		parent::init();

		$this->api->jquery->addStaticInclude('elrte/js/elrte.min');
		$this->api->jquery->addStaticStyleSheet('elrte/css/elrte.min');

		$this->api->auth->setModel('Member','username','password');
		$this->api->auth->addHook('updateForm',array($this,'update_login_form'));
		if(!$this->api->auth->isLoggedIn()){
			// $this->api->template->tryDel('top_advert_position');
			$this->api->template->tryDel('shrink');
			$this->api->template->tryDel('left_advert_position');
			$this->api->template->tryDel('right_advert_position');
			$this->api->template->trySet('center_span',12);
		}
		$this->api->auth->check();
		$this->setUpMemberMenus();

		$this->add('View_ModuleHeading',null,'welcome')->set("Welcome " . $this->api->auth->model['name'] )->sub(str_replace("page", "", str_replace("_", "/", $_GET['page'])));
	}

	function update_login_form($auth){

		$title = $auth->form->add('H2')->set('Login to your account');

		// $register_btn=$auth->add('Button')->set('Register Now Free')->addClass('atk-row span12');
		// $register_btn->js('click')->univ()->frameURL('Register Your Self',$this->api->url('memberpanel_page_register'));

		// $auth->form->add('H4')->set("Don't have account...");
		// $auth->form->add($register_btn);
		$auth->form->add('Order')->move($title,'first')->now();

	}

	function setUpMemberMenus(){
		$this->add('Menu',array('inactive_menu_class'=>'','current_menu_class'=>'active'),'sidebar',array('sidebarmenu'))
			->addMenuItem('memberpanel_page_dashboard','Home')
			->addMenuItem('businessdirectory_page_member_index','My Business Listings')
			->addMenuItem('blooddoner_page_member_index','Blood Dooner')
			->addMenuItem('memberpanel_page_profile','Profile')
			->addMenuItem('socialdirectory_page_member_index','Social Details')
			->addMenuItem('jobandvacancy_page_member_index','My Job & Vacancy')
			->addMenuItem('salesandpurchase_page_member_index','My Sales & Purchase')
			->addMenuItem('event_page_member_index','Registered Events')
			->addMenuItem('memberhelper_page_member_index','Member Helper')
			->addMenuItem('logout');
		;
	}

	function defaultTemplate(){
			$l=$this->api->locate('addons','memberpanel', 'location');
			$this->api->pathfinder->addLocation(
				$this->api->locate('addons','memberpanel'),
				array(
			  		'template'=>'templates',
			  		'css'=>'templates/css'
					)
				)->setParent($l);

		return array('page/memberpanel');
	}

}