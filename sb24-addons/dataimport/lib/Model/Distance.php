<?php

namespace dataimport;

class Model_Distance extends \Model_Table {
	var $table= "old_distance";
	function init(){
		parent::init();
		$this->addField('from');
		$this->addField('to');
		$this->addField('rail');
		$this->addField('road');
		$this->addField('air');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}