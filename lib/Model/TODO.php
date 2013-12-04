<?php
class Model_TODO extends Model_Table {
	var $table= "member_TODO";
	function init(){
		parent::init();

		$this->hasOne('Member','member_id');
		$this->addField('name')->caption('Task');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'));
		$this->addField('remaind_on')->type('date');
		$this->addField('by_sms')->type('boolean');
		$this->addField('by_email')->type('boolean');
		$this->addField('is_sent')->type('boolean');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}