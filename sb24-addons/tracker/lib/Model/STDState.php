<?php


namespace tracker;

class Model_STDState extends \Model_Table {
	var $table= "std_state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('tracker/STDDistrict','state_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}