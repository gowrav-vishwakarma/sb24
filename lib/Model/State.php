<?php

class Model_State extends Model_Table {
	var $table= "state";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('City','state_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}