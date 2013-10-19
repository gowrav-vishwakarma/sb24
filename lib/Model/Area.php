<?php

class Model_Area extends Model_Table {
	var $table= "area";
	function init(){
		parent::init();

		$this->hasOne('Tehsil','area_id');
		$this->addField('name');
		$this->hasMany('History_Place','area_id');
		$this->hasMany('SocialDirectory_Listing','area_id');
		$this->hasMany('BusinessDirectory_Listing','area_id');
		$this->hasMany('JobAndVacancy_Listing','area_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}