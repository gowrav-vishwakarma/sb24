<?php

class page_event_page_register extends page_base_site {
	function init(){
		parent::init();
		$this->api->auth->setModel('Member','username','password');
		if(!$this->api->auth->isLoggedIn()){
			$this->add('View_Info')->set('You must be logged in to register.');
			$this->js(true)->univ()->redirect('memberpanel_page_base');
			return;
		} 

		$register = $this->add('event/Model_Registration');
		$register->addCondition('event_id',$_GET['listing_id']);
		$register->addCondition('member_id',$this->api->auth->model->id);
		$register->tryLoadAny();

		if($register->loaded()){
			$this->add('View_Error')->set("You are Already Registered for this event");
			return;
		}else{
			$register->save();
			$this->add('View_Info')->set("You are now Registered for this event");
		}

	}
}