<?php

class page_businessdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$business_listing_tab=$tabs->addTab('Manage Business Directory');
		$business_listing_crud=$business_listing_tab->add('CRUD');
		$business_listing_crud->setModel('businessdirectory/Listing');

		$business_listing_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'city_id','city_id'=>'state_id','subcategory_id'=>'category_id')));
		
		if($f=$business_listing_crud->form){
			if($f=$business_listing_crud->form){
				$wz=$f->add('Controller_FormWizard');
				$wz->addStep('Initial Info','member_id');
				$wz->addStep('Basic Info','state_id');
				$wz->addStep('Extended Info','name');
				$wz->go();
			}

		}

		$tabs->addtabURL('businessdirectory/page_admin_report','Reports');		
	}
}