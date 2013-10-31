<?php

class page_businessdirectory_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		
		$paid_model_listing=$this->add('businessdirectory/Model_Listing');
		$paid_model_listing->addCondition('member_id',$this->api->auth->model->id);
		$paid_model_listing->addCondition('is_paid',true);

		$tabs=$this->add('Tabs');
		$tab_free=$tabs->addTab('Free Listing');
		$tab_paid=$tabs->addTab('Paid Listing');

		$this->free_tab_entries($tab_free);

		
		$tab_paid->add('H3')->set('Your Business Listings')->sub("Manage Paid listings for your business");
		
		$paid_listing_crud = $tab_paid->add('CRUD',array('allow_add'=>false));
		if($paid_listing_crud->form)
		$paid_model_listing->getElement('is_active')->system(true);
		$paid_listing_crud->setModel($paid_model_listing,null,array('name','mobile_no','is_paid'));
		$paid_listing_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'city_id','city_id'=>'state_id','segment_id'=>'industry_id')));

		if($f=$paid_listing_crud->form){
			$wz=$f->add('Controller_FormWizard');
			$wz->addStep('Initial Info','member_id');
			$wz->addStep('Basic Info','name');
			$wz->addStep('About us','about_us');
			$wz->addStep('Contact us','contact_person');
			$wz->addStep('Gallary','gallery_image_1_id');
			$wz->addStep('Product & Service','products_image_1_id');
			$wz->addStep('Map','map_latitute_longitude');
			$wz->go();
		}

	}


	function free_tab_entries($tab_free){
		$tab_free->add('H3')->set('Your Business Listings')->sub("Add Free listings for your business");

		$business_listing_crud = $tab_free->add('CRUD');
		$freelisting_model_listing=$this->add('businessdirectory/Model_Listing');
		$freelisting_model_listing->addCondition('member_id',$this->api->auth->model->id);
		$freelisting_model_listing->addCondition('is_paid',false);

		$business_listing_crud->setModel($freelisting_model_listing,'free',array('name','mobile_no','is_active'));
		$business_listing_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'city_id','city_id'=>'state_id','segment_id'=>'industry_id')));
		
	}
		
}