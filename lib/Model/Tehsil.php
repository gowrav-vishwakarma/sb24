<?php
class Model_Tehsil extends Model_Table {
	var $table= "tehsil";
	function init(){
		parent::init();

		$this->hasOne('State','state_id')->sortable(true);
		$this->hasOne('City','city_id')->sortable(true);
		$this->addField('name')->sortable(true);
		$this->hasMany('Area','tehsil_id');
		$this->hasMany('event/Listing','tehsil_id');

		$this->addExpression('no_of_areas')->set(function($m,$q){
			return $m->refSQL('Area')->count();
		});

		$this->addHook('beforeDelete',$this);

		 $this->_dsql()->order('name','asc');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('Area')->count()->getOne() > 0 )
			throw $this->exception('Cannot Delete Tehsil, It Contains Areas defined','validityCheck')->setField('name')->addMoreInfo('Tehsil',$this['name']);
	}
}