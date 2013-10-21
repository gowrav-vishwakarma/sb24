<?php

class page_businessdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$business_listing_tab=$tabs->addTab('Manage Business Directory');
		$business_listing_crud=$business_listing_tab->add('CRUD');
		$business_listing_crud->setModel('businessdirectory/Listing');

		if($f=$business_listing_crud->form){
			$sub_category_field = $f->getElement('subcategory_id');
			if($_GET['category_id']) $sub_category_field->model->addCondition('category_id',$_GET['category_id']);
			// $sub_category_field->setAttr('multiple','multiple');

			$category_field = $f->getElement('category_id');
			$category_field->js('change',$f->js()->atk4_form('reloadField','subcategory_id',array($this->api->url(),'category_id'=>$category_field->js()->val())));

		}
		
		$tabs->addtabURL('businessdirectory/page_admin_report','Reports');		
	}
}