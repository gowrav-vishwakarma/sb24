<?php

class page_salesandpurchase_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		
		$crud = $this->add('CRUD');
		$myproducts=$this->api->auth->model->ref('salesandpurchase/Listing');
		$crud->setModel($myproducts,
			array('state_id','city_id','tehsil_id','area_id','category_id','subcategory_id','name','price','description','product_image_1_id','product_image_2_id','product_image_3_id'),
			array('category','subcategory','name','price')
			);

		$crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id')));

	}
}