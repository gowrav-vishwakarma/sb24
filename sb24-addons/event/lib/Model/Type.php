<?php

namespace event;

class Model_Type extends \Model_Table {
	var $table= "event_type";
	
	function init(){
		parent::init();

		$this->addField('name');

		$this->hasMany('event/Listing','type_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}