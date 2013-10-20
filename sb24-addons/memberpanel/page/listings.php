<?php

class page_memberpanel_page_listings extends page_memberpanel_page_base {
	function init(){
		parent::init();
		
		$tabs = $this->add('Tabs');
		$business_listing_tab = $tabs->addTab('Business Listing');
		$business_listing_crud = $business_listing_tab->add('CRUD',array('allow_add'=>false));
		$business_listing_crud->setModel($this->api->auth->model->ref('businessdirectory/FreeListing'));


	}
}