<?php

class Model_History_PlaceType extends Model_Table {
	var $table= "placetype";
	function init(){
		parent::init();

		$this->addField('name')->caption('Type Of Place');
		$this->hasMany('History_Place','placetype_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('Model_History_PlaceType');
		$old_model->addCondition('name',$this['name']);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Type is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
			


	}

	function beforeDelete(){

	}
}