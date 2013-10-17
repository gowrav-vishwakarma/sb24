<?php
class Model_Directory_Cast extends Model_Table {
	var $table= "cast";
	function init(){
		parent::init();

		$this->addField('name')->Caption('Cast');
		$this->hasMany('Directory_Core','cast_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}