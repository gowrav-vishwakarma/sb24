<?php

namespace tracker;

class Model_MobileListing extends \Model_Table {
	var $table= "tracker_mobile_listing";
	function init(){
		parent::init();

		$this->hasOne('tracker/MobileCompany','company_id');
		$this->hasOne('State','state_id');
		$this->addField('series');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}