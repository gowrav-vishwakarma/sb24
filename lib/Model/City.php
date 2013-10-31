<?php

class Model_City extends Model_Table {
	var $table= "city";
	function init(){
		parent::init();

		$this->hasOne('State','state_id')->sortable(true);
		$this->addField('name')->sortable(true);
		$this->hasMany('Tehsil','city_id');
		$this->hasMany('Area','city_id');

		$this->addExpression('no_of_tehsils')->set(function($m,$q){
			return $m->refSQL('Tehsil')->count();
		});

		$this->addHook('beforeDelete',$this);
		$this->setOrder('name');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('Tehsil')->count()->getOne() > 0 )
			throw $this->exception('City cannot be deleted, as it contains Tehsils','ValidityCheck')->setField('name')->addMoreInfo('city',$this['name']);
		if($this->ref('Area')->count()->getOne() > 0 )
			throw $this->exception('City cannot be deleted, as it contains Areas','ValidityCheck')->setField('name')->addMoreInfo('city',$this['name']);
	}
}