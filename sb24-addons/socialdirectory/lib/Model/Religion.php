<?php

namespace socialdirectory;

class Model_Religion extends \Model_Table {
	var $table= "religion";
	function init(){
		parent::init();

		$this->addField('name')->Caption('Religion');
		$this->hasMany('Member','religion_id');

		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('socialdirectory/Model_Religion');
		$old_model->addCondition('name',$this['name']);
		$old_model->addCondition('id','<>',$this->id);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Religion is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
	}
}