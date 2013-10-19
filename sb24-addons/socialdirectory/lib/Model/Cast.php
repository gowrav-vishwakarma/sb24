<?php

namespace socialdirectory;

class Model_Cast extends \Model_Table {
	var $table= "cast";
	function init(){
		parent::init();

		$this->addField('name')->Caption('Cast');
		$this->hasMany('socialdirectory/SubCast','cast_id');
		$this->hasMany('socialdirectory/Listing','cast_id');

		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('socialdirectory/Model_Cast');
		$old_model->addCondition('name',$this['name']);
		$old_model->addCondition('id','<>',$this->id);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Cast is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
	}
}