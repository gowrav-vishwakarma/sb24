<?php

namespace distance;

class Model_Listing extends \Model_Table {
	var $table= "distance_listing";
	function init(){
		parent::init();

		$this->hasOne('distance/City','city_1_id');
		$this->hasOne('distance/City','city_2_id');
		$this->addField('distance_bus');
		$this->addField('distance_train');
		$this->addField('distance_plane');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$existing = $this->add('distance/Model_Listing');
		$existing->addCondition('city_2_id',$this['city_1_id']);
		$existing->addCondition('city_1_id',$this['city_2_id']);
		$existing->addCondition('id','<>',$this->id);
		$existing->tryLoadAny();

		$existing1 = $this->add('distance/Model_Listing');
		$existing1->addCondition('city_1_id',$this['city_1_id']);
		$existing1->addCondition('city_2_id',$this['city_2_id']);
		$existing1->addCondition('id','<>',$this->id);
		$existing1->tryLoadAny();

		if($existing1->loaded()){
			throw $this->exception('This distance is already fed  ','ValidityCheck' )->setField('city_1_id');
		}
			
		if($existing->loaded()){
			throw $this->exception('This distance is already fed as ' . $existing['distance'],'ValidityCheck' )->setField('city_1_id');
		}
		
	}
}