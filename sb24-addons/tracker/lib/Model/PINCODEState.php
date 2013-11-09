<?php

namespace tracker;

class Model_PINCODEState extends \Model_Table {
	var $table= "pincode_state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('tracker/PINCODEDistrict','state_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}