<?php
class Model_Questions extends Model_Table {
	var $table= "questions";
	function init(){
		parent::init();
		$this->addField('name')->caption('Question');
		
		$this->add('dynamic_model/Controller_AutoCreator');
		$this->hasMany('Memeber','question_id');
	}
}