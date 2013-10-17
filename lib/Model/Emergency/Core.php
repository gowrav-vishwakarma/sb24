<?php

class Model_Emergency_Core extends Model_Table {
	var $table= "employee";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');

		$this->addField('name');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}