<?php
class Model_Directory_Religion extends Model_Table {
	var $table= "religion";
	function init(){
		parent::init();

		$this->addField('name')->Caption('Religion');
		$this->hasMany('Directory_Core','religion_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}