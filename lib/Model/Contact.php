<?php
class Model_Contact extends Model_Table{
	public $table='admin_contact';
	function init(){
		parent::init();
		$this->addField('name')->mandatory(true);
		$this->addField('mobile_no')->mandatory(true);
		$this->addField('email')->mandatory(true);
		$this->addField('message')->type('text')->mandatory(true);
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}