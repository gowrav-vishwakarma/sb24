<?php
namespace salesandpurchase;
class Model_Listing extends \Model_Table {
 	var $table= "sales_and_purchase";
 	function init(){
 		parent::init();

 		$member = $this->hasOne('Member','member_id');
 		if($this->api->auth->model AND !$this->api->auth->model['is_staff']){
			$member->defaultValue($this->api->auth->model->id);
 		}
		
 		$this->hasOne('State','state_id');
 		$this->hasOne('City','city_id');
 		$this->hasOne('Tehsil','tehsil_id');
 		$this->hasOne('Area','area_id');
 		$this->hasOne('salesandpurchase/Category','category_id');
 		$this->hasOne('salesandpurchase/SubCategory','subcategory_id');
 		$this->addField('name')->caption('Name Of Product');
 		$this->addField('price');
 		$this->addField('description')->type('text')->display(array('form'=>'RichText'));
 		// $this->addField('short_description	')->type('text')->display(array('form'=>'RichText'));
 		$this->addField('is_sold')->type('boolean');
 		$this->addField('posting_type')->defaultValue('sale')->enum(array('sales','requirement'));
 		$this->addField('sold_date')->type('date');
 		$this->addField('search_string')->system(true);
 		$this->add("filestore/Field_Image","product_image_1_id")->type('image');
 		$this->add("filestore/Field_Image","product_image_2_id")->type('image');
 		$this->add("filestore/Field_Image","product_image_3_id")->type('image');
 		

 		$this->addHook('beforeSave',$this);
 		$this->add('dynamic_model/Controller_AutoCreator');
 	}

 	function beforeSave(){

		$this['search_string']= $this->ref('category_id')->get('name') . " ".
								$this->ref('subcategory_id')->get('name') . " ".
								$this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('member_id')->get('name'). " ".
								$this['price']
							;

	}

	function markSold(){
		$this['is_sold']=true;
		$this['sold_date']=date('Y-m-d H:i:s');
		$this->save();
	}

}
 