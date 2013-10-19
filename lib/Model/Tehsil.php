<?php
class Model_Tehsil extends Model_Table {
	var $table= "tehsil";
	function init(){
		parent::init();

		$this->hasOne('City','city_id');
		$this->addField('name');
		$this->hasMany('Area','tehsil_id');
		$this->hasMany('History_Place','tehsil_id');
		$this->hasMany('SocialDirectory_Listing','tehsil_id');
		$this->hasMany('BusinessDirectory_Listing','tehsil_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}