<?php

namespace tracker;

class Model_RTOListing extends \Model_Table {
	var $table= "rtocode_Listing";
	function init(){
		parent::init();

		$this->hasOne('tracker/RTOState','state_id');
		$this->addField('area');
		$this->addField('name')->caption('RTO Code');
		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if($this['state_id']=="")
			throw $this->exception('State Must be Selected','ValidityCheck')->setField('state_id');
	}
}