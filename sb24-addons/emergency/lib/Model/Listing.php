<?php

namespace emergency;

class Model_Listing extends \Model_Table {
	var $table= "emergency";
	function init(){
		parent::init();

		
		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');
		$this->hasOne('emergency/Category','category_id');	
		

		$this->addField('name');
		$this->addField('number');
		$this->addField('search_string')->system(true);

		$this->addHook('beforeSave',$this);


		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$this['search_string']=
								$this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('tehsil_id')->get('name'). " ".
								$this['name']. " ".
								$this["number"]
							;
	}
}