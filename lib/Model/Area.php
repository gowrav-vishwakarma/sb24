<?php

class Model_Area extends Model_Table {
	var $table= "area";
	function init(){
		parent::init();

		$this->hasOne('Tehsil','tehsil_id');
		$this->addField('name');
		$this->hasMany('Directory_Core','tehsil_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}