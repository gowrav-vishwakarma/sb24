<?php

class Model_State extends Model_Table {
	var $table= "state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('City','state_id');
		// $this->hasMany('History_Place','state_id');
		// $this->hasMany('SocialDirectory_Listing','state_id');
		// $this->hasMany('BusinessDirectory_Listing','state_id');
		// $this->hasMany('JobAndVacancy_Listing','state_id');
		// $this->hasMany('SalesAndPurchse_Listing','state_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}