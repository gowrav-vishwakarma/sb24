<?php

class page_salesandpurchase_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		$tabs=$this->add('Tabs');
		$sales_tab=$tabs->addTab('Sales Listing');
		$purchase_tab=$tabs->addTab('Requirement Listing');
		$crud = $sales_tab->add('CRUD');
		$myproducts=$this->api->auth->model->ref('salesandpurchase/Listing');
		$myproducts->addCondition('posting_type','sales');
		$crud->setModel($myproducts,
			array('state_id','city_id','tehsil_id','area_id','category_id','subcategory_id','name','price','description','product_image_1_id','product_image_2_id','product_image_3_id'),
			array('category','subcategory','name','price')
			);

		$crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id')));

		$purchase_crud=$purchase_tab->add('CRUD');
		$purchase=$this->api->auth->model->ref('salesandpurchase/Listing');
		$purchase->addCondition('posting_type','requirement');
		if($purchase_crud->form){
			$purchase->getElement('is_sold')->system(true);
			$purchase->getElement('sold_date')->system(true);
			$purchase->getElement('product_image_1_id')->system(true);
			$purchase->getElement('product_image_2_id')->system(true);
			$purchase->getElement('product_image_3_id')->system(true);
		}
		$purchase_crud->setModel($purchase,array('category','subcategory','name','price'),null);
		$purchase_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id')));
	}
}