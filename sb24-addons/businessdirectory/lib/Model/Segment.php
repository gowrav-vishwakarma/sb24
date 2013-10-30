<?php

namespace businessdirectory;

class Model_Segment extends \Model_Table {
	var $table= "businessdirectory_segment";
	function init(){
		parent::init();

		$this->hasOne('businessdirectory/Industry','industry_id');
		$this->addField('name');
		$this->hasMany('businessdirectory/Listing','segment_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}