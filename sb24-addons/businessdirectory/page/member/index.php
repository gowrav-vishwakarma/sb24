<?php

class page_businessdirectory_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		$this->add('H3')->set('Your Business Listings')->sub("Add Free listings for your business");
		$business_listing_crud = $this->add('CRUD',array('allow_add'=>false));
		$business_listing_crud->setModel($this->api->auth->model->ref('businessdirectory/Listing'),'free',array('name','mobile_no','is_paid'));
	}
}