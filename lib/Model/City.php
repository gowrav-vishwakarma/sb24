<?php

class Model_City extends Model_Table {
	var $table= "city";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->addField('name');
		$this->hasMany('Tehsil','city_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}