<?php

namespace dataimport;

class Model_OldNumber extends \Model_Table {
	var $table= "old_mobile_series";
	function init(){
		parent::init();

		$this->addField('series');
		$this->addField('company');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}