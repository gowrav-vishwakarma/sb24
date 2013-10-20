<?php

namespace history;

class Model_Place extends \Model_Table {
	var $table= "place";
	function init(){
		parent::init();

		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/PlusCrud'));
		$this->hasOne('history/PlaceType','placetype_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->addField('name');
		$this->addField('description')->type('text');
		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('history/Model_Place');
		$old_model->addCondition('name',$this['name']);
		$old_model->addCondition('id','<>',$this->id);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Place is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
			


	}
}