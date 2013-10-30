<?php

namespace dataimport;

class Model_Areas extends \Model_Table {
	var $table= "old_areas";
	function init(){
		parent::init();

		$this->addField('district');
		$this->addField('tehsil');
		$this->addField('area');
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}