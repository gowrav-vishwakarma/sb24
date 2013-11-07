<?php

namespace dataimport;

class Model_RTOCode extends \Model_Table {
	var $table= "old_rto";
	function init(){
		parent::init();

		$this->addField('state');
		$this->addField('area');
		$this->addField('rtocode');
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}