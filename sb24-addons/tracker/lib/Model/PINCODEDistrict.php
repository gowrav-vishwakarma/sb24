<?php

namespace tracker;

class Model_PINCODEDistrict extends \Model_Table {
	var $table= "pincode_district";
	function init(){
		parent::init();

		$this->hasOne('tracker/PINCODEState','state_id');
		$this->addField('name');
		$this->hasMany('PINCODEListing','district_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}