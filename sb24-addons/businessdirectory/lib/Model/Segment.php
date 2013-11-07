<?php

namespace businessdirectory;

class Model_Segment extends \Model_Table {
	var $table= "businessdirectory_segment";
	function init(){
		parent::init();

		$this->hasOne('businessdirectory/Industry','industry_id');
		$this->addField('name');
		$this->hasMany('businessdirectory/Listing','segment_id');

		$this->addExpression('no_of_listings')->set(function ($m,$q){
			return $m->refSQL('businessdirectory/Listing')->count();
		});

		$this->_dsql()->order('name','asc');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}


	function beforeDelete(){
		if($this->ref('businessdirectory/Listing')->count()->getOne() > 0)
			throw $this->exception('Segement contains Listings in it, cannot delete','ValidityCheck')->setField('name')->addMoreInfo('Segment',$this['name']);
	
	}
}