<?php

namespace dataimport;

class Model_PinCode extends Model_Table {
	var $table= "old_pincode";
	function init(){
		parent::init();

		$this->addField('state');
		$this->addField('district');
		$this->addField('area');
		$this->addField('pincode');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}