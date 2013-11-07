<?php

namespace socialdirectory;

class Model_Cast extends \Model_Table {
	var $table= "social_cast";
	function init(){
		parent::init();

		$this->hasOne('socialdirectory/Religion','religion_id');
		$this->addField('name')->Caption('Cast');
		$this->hasMany('socialdirectory/SubCast','cast_id');
		$this->hasMany('Member','cast_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
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

	function beforeDelete(){
		if($this->ref('socialdirectory/SubCast')->count()->getOne() > 0)
			throw $this->exception('You Cannot delete this cast, it contained defined sub casts','ValidityCheck')->setField('name')->addMoreInfo('Cast',$this['name']);
	}
}