<?php

class Model_Area extends Model_Table {
	var $table= "area";
	function init(){
		parent::init();

		$this->hasOne('State','state_id')->sortable(true);
		$this->hasOne('City','city_id')->sortable(true);
		$this->hasOne('Tehsil','tehsil_id')->sortable(true);
		$this->addField('name')->sortable(true);
		// $this->hasMany('History_Place','area_id');
		// $this->hasMany('SocialDirectory_Listing','area_id');
		// $this->hasMany('BusinessDirectory_Listing','area_id');
		// $this->hasMany('JobAndVacancy_Listing','area_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}