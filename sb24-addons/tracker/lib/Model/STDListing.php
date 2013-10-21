<?php

namespace tracker;

class Model_STDListing extends \Model_Table {
	var $table= "std_Listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');

		$this->addField('area');
		$this->addField('STD_code');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if($this['state_id']=="")
			throw $this->exception('State cannot be empty','ValidityCheck')->setField('state_id');
		if($this['city_id']=="")
			throw $this->exception('City cannot be empty','ValidityCheck')->setField('city_id');

	}
}