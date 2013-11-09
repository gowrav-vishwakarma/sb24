<?php

namespace businessdirectory;

class Model_RegisteredCategory extends \Model_Table {
	var $table= "businessdirectory_registeredcategory";
	function init(){
		parent::init();
		$this->hasOne('SubCategory','subcategory_id');
		$this->hasOne('businessdirectory/Listing','listing_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}