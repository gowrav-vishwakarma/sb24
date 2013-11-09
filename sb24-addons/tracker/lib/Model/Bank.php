<?php

namespace tracker;

class Model_Bank extends \Model_Table {
	var $table= "bank_listing";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('tracker/MIRCListing','bank_id');

		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_bank = $this->add('tracker/Model_Bank');
		$old_bank->addCondition('name',$this['name']);
		if($this->loaded())
			$old_bank->addCondition('id','<>',$this->id);
		$old_bank->tryLoadAny();
		if($old_bank->loaded())
			throw $this->exception('This Bank Name is already entered','ValidityCheck')->setField('name');
		
	}
}