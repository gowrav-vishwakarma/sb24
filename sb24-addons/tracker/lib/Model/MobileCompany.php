<?php
class Model_MobileCompany extends Model_Table {
	var $table= "tracker_mobile_company";
	function init(){
		parent::init();
		$this->addField('name');
		$this->hasMany('tracker/MobileListing','company_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}