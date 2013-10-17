<?php

namespace emergency;

class Model_Emergeecy extends \Model_Table {
	var $table= "emergency";
	function init(){
		parent::init();

		
		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');
		

		$this->addField('name');
		$this->addField('number');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}