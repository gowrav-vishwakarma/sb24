<?php

namespace emergency;

class Model_Category extends \Model_Table {
	var $table= "emergency_category";
	
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('emergency/Listing','category_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}