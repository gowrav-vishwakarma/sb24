<?php

class page_businessdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$business_listing_tab=$tabs->addTab('Manage Business Directory');
		$business_listing_crud=$business_listing_tab->add('businessdirectory/CRUD');
		$business_listing_crud->setModel('businessdirectory/Listing');

		if($business_listing_crud->form){
			$sub_category_field = $business_listing_crud->form->addField('dropdown','sub_category');
			$sub_category_model = $this->add('Model_SubCategory');
			$sub_category_model->addCondition('category_id',$_GET['category_id']);
			$sub_category_field->setModel($sub_category_model);
			$sub_category_field->setAttr('multiple','multiple');

			$category_field = $business_listing_crud->form->getElement('category_id');
			$category_field->js('change',$business_listing_crud->form->js()->atk4_form('reloadField','sub_category',array($this->api->url(),'category_id'=>$category_field->js()->val())));

		}
		
		$tabs->addtabURL('businessdirectory/page_admin_report','Reports');		
	}
}