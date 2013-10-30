<?php

class Model_City extends Model_Table {
	var $table= "city";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->addField('name');
		// $this->hasMany('History_Place','city_id');
		// $this->hasMany('SocialDirectory_Listing','city_id');
		// $this->hasMany('BusinessDirectory_Listing','city_id');
		// $this->hasMany('SalesAndPurchase_Listing','city_id');
		// $this->hasMany('Tehsil','city_id');
		// $this->hasMany('JobAndVacancy_Listing','city_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}