<?php
class page_memberpanel_page_todo extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$crud->setModel($this->api->auth->model->ref("TODO"),array('name','remaind_on','by_sms','by_email'),array('name','remaind_on','created_at','by_sms','by_email'));
	}
}