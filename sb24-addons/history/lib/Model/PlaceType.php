<?php

namespace history;

class Model_PlaceType extends \Model_Table {
	var $table= "placetype";
	function init(){
		parent::init();

		$this->addField('name')->caption('Type Of Place');
		$this->hasMany('history/Place','placetype_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('history/Model_PlaceType');
		$old_model->addCondition('name',$this['name']);
		$old_model->addCondition('id','<>',$this->id);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Type is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
	}

	function beforeDelete(){
		if($this->ref('History_Place')->count()->getOne()> 0)
			throw $this->exception("You Can't Delete, It contain places...");
			

	}
}