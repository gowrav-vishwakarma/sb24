<?php
class Model_Staff extends Model_Table {
	var $table= "staff";
	function init(){
		parent::init();

		$this->addField('username');
		$this->addField('password');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}