<?php

class page_businessdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$business_listing_tab=$tabs->addTab('Manage Business Directory');

		$category_tab=$tabs->addTab('Business Industries');
		$category_crud=$category_tab->add('CRUD');
		$category_crud->setModel('businessdirectory/Industry');
		$category_crud->addRef('businessdirectory/Segment');
		if($bcg=$category_crud->grid){
			$bcg->addPaginator(20);
			$bcg->addQuickSearch(array('name'));
		}

		$business_listing_crud=$business_listing_tab->add('CRUD');
		$model=$this->add('businessdirectory/Model_Listing');

		$model->getElement('is_paid')->system(false);
		$model->getElement('is_active')->system(false);

		if($_GET['activate']){
			$activate_model=$this->add('businessdirectory/Model_Listing');
			$activate_model->load($_GET['activate']);
			$activate_model->markActivate();
		if($g=$business_listing_crud->grid)
			$g->js()->reload()->execute();
		}
		// else
		// $business_listing_crud->grid->js()->univ()->errorMessage("This is Already Activate")->execute();
		// $business_listing_crud->grid->js()->reload()->execute();
		
		$business_listing_crud->setModel($model,null,array('name','state','city','tehsil','area','company_address','company_ph_no','email_id','contact_person','payment_received','is_active','is_paid','created_on','username','password'));
		if($blg=$business_listing_crud->grid){
			$blg->js(true)->_load('footable')->_selector('#'.$blg->name.' table')->footable();
			$this->api->jquery->addStyleSheet('footable.core');
			$blg->columns['company_address']['thparam']='data-hide="all"';
			$blg->columns['company_ph_no']['thparam']='data-hide="all"';
			$blg->columns['state']['thparam']='data-hide="all"';
			$blg->columns['email_id']['thparam']='data-hide="all"';
			$blg->columns['payment_received']['thparam']='data-hide="all"';
			$blg->columns['tehsil']['thparam']='data-hide="all"';
			$blg->columns['area']['thparam']='data-hide="all"';
			$blg->columns['username']['thparam']='data-hide="all"';
			$blg->columns['password']['thparam']='data-hide="all"';
			$blg->addColumn('Button','activate');
			$blg->addPaginator(20);
			$blg->addQuickSearch(array('name','state','city','tehsil','area','email_id'));
		}
		$business_listing_crud->addRef('businessdirectory/PayAmount');
		$business_listing_crud->addRef('businessdirectory/ProductImages');
		$business_listing_crud->addRef('businessdirectory/GallaryImages');
		$business_listing_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'city_id','city_id'=>'state_id','segment_id'=>'industry_id'),'force_selection'=>false));
		
		// if($f=$business_listing_crud->form){
		// 	if($f=$business_listing_crud->form){
		// 		$wz=$f->add('Controller_FormWizard');
		// 		$wz->addStep('Initial Info','member_id');
		// 		$wz->addStep('Basic Info','state_id');
		// 		$wz->addStep('Extended Info','name');
		// 		$wz->go();
		// 	}

		// }

		// $tabs->addtabURL('businessdirectory/page_admin_report','Reports');		
	}
}

