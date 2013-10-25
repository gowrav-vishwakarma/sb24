<?php

namespace businessdirectory;

class Model_PayAmount extends \Model_Table {
		var $table="listing_payment";
	function init(){
		parent::init();

		$this->hasOne('businessdirectory/Listing','listing_id');
		$this->addField('name')->type('int')->caption('Amount');
		$this->addField('submitted_on')->type('date');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}