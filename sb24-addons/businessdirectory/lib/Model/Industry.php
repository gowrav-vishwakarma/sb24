<?php

namespace businessdirectory;

class Model_Industry extends \Model_Table {
	var $table= "businessdirectory_industry";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('businessdirectory/Segment','industry_id');
		$this->hasMany('businessdirectory/Listing','industry_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}