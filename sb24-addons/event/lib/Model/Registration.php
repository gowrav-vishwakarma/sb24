<?php

namespace event;

class Model_Registration extends \Model_Table {
	var $table= "event_registration";
	function init(){
		parent::init();

		$this->hasOne('event/Listing','event_id');
		$this->hasOne('Member','member_id');
		$this->addField('registration_date')->type('date')->defaultValue(date('Y-m-d H:i:s'));

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}