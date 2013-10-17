<?php

class Model_BusinessCategory extends Model_Table {
	var $table= "businesscategory";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('BusinessSubCategory','businesscategory_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}