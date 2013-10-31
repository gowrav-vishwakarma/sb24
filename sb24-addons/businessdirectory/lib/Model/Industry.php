<?php

namespace businessdirectory;

class Model_Industry extends \Model_Table {
	var $table= "businessdirectory_industry";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('businessdirectory/Segment','industry_id');
		$this->hasMany('businessdirectory/Listing','industry_id');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('businessdirectory/Segment')->count()->getOne() > 0)
			throw $this->exception('Industry contains Segments defined, cannot delete','ValidityCheck')->setField('name')->addMoreInfo('Industry',$this['name']);
		if($this->ref('businessdirectory/Listing')->count()->getOne() > 0)
			throw $this->exception('Industry contains Listings in it, cannot delete','ValidityCheck')->setField('name')->addMoreInfo('Industry',$this['name']);
	}
}