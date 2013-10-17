<?php
class Model_BusinessSubCategory extends Model_Table {
	var $table= "business_sub_category";
	function init(){
		parent::init();

		$this->hasOne('BusinessCategory','businesscategory_id');
		$this->addField('name');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}