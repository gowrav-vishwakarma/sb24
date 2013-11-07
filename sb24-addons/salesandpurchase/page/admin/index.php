<?php

class page_salesandpurchase_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tab_category=$tabs->addTab('Sales & Purchase Category');
		$category_crud=$tab_category->add('CRUD');
		$category_model=$this->add('salesandpurchase/Model_Category');
		$category_crud->setModel($category_model);
		
		$tab_subcategory=$tabs->addTab('Sales & Purchase SubCategory');
		$subcategory_crud=$tab_subcategory->add('CRUD');
		$subcategory_model=$subcategory_crud->add('salesandpurchase/Model_SubCategory');
		$subcategory_crud->setModel($subcategory_model);
		

		$tab_listing=$tabs->addTab('Sales & Purchase');
		$listing_crud=$tab_listing->add('CRUD');
		$listing_crud->setModel('salesandpurchase/Model_Listing');

		$listing_crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id','tehsil_id'=>'city_id','area_id'=>'tehsil_id','subcategory_id'=>'category_id'),'force_selection'=>true));
	}
}

