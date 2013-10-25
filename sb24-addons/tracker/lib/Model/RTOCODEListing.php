<?php

namespace tracker;

class Model_RTOCODEListing extends \Model_Table {
	var $table= "rtocode_Listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->addField('name')->caption('RTO Code');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if($this['state_id']=="")
			throw $this->exception('State Must be Selected','ValidityCheck')->setField('state_id');
		if($this['city_id']=="")
			throw $this->exception('City Must be Selected','ValidityCheck')->setField('city_id');
	}
}