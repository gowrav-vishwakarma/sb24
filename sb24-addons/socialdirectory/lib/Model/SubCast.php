<?php

namespace socialdirectory;

class Model_SubCast extends \Model_Table {
	var $table= "soaicl_subcast";
	function init(){
		parent::init();

		$this->hasOne('socialdirectory/Cast','cast_id');
		$this->addField('name')->Caption('Sub Cast');
		$this->hasMany('Member','subcast_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}