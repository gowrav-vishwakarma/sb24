<?php

namespace distance;


class Model_City extends \Model_Table {
	var $table= "distance_city";
	function init(){
		parent::init();
		$this->addField('name');
		$this->hasMany('distance/Listing','city_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}