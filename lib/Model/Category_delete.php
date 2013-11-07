<?php

class Model_Category extends Model_Table {
	var $table= "category";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('SubCategory','category_id');
		$this->hasMany('businessdirectory/Listing','category_id');
		// $this->hasMany('businessdirectory/RegisteredCategory','category_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}