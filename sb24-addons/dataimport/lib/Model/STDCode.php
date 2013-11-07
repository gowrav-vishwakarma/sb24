<?php

namespace dataimport;

class Model_STDCode extends \Model_Table {
	var $table= "old_std";
	function init(){
		parent::init();

		$this->addField('state');
		$this->addField('district');
		$this->addField('area');
		$this->addField('stdcode');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}