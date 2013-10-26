<?php

namespace tracker;

class Model_STDDistrict extends \Model_Table {
	var $table= "std_district";
	function init(){
		parent::init();

		$this->hasOne('tracker/STDState','state_id');
		$this->addField('name');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}