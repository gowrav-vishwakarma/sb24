<?php

class Model_Distance_Core extends Model_Table {
	var $table= "distance";
	function init(){
		parent::init();

		$this->hasOne('City','city_from_id');
		$this->hasOne('City','city_to_id');

		$this->addField('bus_distance');
		$this->addField('train_distance');
		$this->addField('plane_distance');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}