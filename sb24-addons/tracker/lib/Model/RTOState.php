<?php

namespace tracker;

class Model_RTOState extends \Model_Table {
	var $table= "rto_state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('tracker/RTOListing','state_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}