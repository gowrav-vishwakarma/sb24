<?php

class page_businessdirectory_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		$tabs = $this->add('Tabs');
		$business_listing_tab = $tabs->addTab('Business Listing');
		$business_listing_crud = $business_listing_tab->add('CRUD',array('allow_add'=>false));
		$business_listing_crud->setModel($this->api->auth->model->ref('businessdirectory/Listing'),'free',array('name','mobile_no','is_paid'));
	}
}