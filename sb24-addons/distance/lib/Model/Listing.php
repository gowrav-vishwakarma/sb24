<?php

namespace distance;

class Model_Listing extends \Model_Table {
	var $table= "distance_listing";
	function init(){
		parent::init();

		$this->hasOne('City','city_1_id');
		$this->hasOne('City','city_2_id');
		$this->addField('distance');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$existing = $this->add('distance/Model_Listing');
		$existing->addCondition('city_2_id',$this['city_1_id']);
		$existing->addCondition('city_1_id',$this['city_2_id']);
		$existing->addCondition('id','<>',$this->id);
		$existing->tryLoadAny();
		if($existing->loaded())
			throw $this->exception('This distance is already fed as ' . $existing['distance'],'ValidityCheck' )->setField('city_1_id');
		
	}
}