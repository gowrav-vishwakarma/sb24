<?php

class Model_State extends Model_Table {
	var $table= "state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('City','state_id');
		$this->hasMany('Tehsil','state_id');
		$this->hasMany('Area','state_id');
		$this->hasMany('tracker/STDListing','state_id');

		$this->addExpression('no_of_cities')->set(function($m,$q){
			return $m->refSQL('City')->count();
		});
		 $this->_dsql()->order('name','asc');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('City')->count()->getOne() > 0) 
			throw $this->exception('State cannot be deleted, it contains cities','ValidityCheck')->setField('name')->addMoreInfo('State',$this['name']);
	}
}