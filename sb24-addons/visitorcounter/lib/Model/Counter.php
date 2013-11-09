<?php

namespace visitorcounter;

class Model_Counter extends \Model_Table {
	var $table= "visitor_counter";
	function init(){
		parent::init();


		$this->addField('on_date')->type('date')->defaultValue(date('Y-m-d'));
		$this->addField('visits');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}