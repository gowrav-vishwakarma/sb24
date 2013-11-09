<?php
namespace tracker;
class Model_MobileCompany extends \Model_Table {
	var $table= "tracker_mobile_company";
	function init(){
		parent::init();
		$this->addField('name');
		$this->add("filestore/Field_Image","company_logo_id")->type('image');
		$this->hasMany('tracker/MobileListing','company_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}