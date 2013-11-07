<?php

namespace adds;

class Model_AdPayment extends \Model_Table {
	var $table= "adpayment";
	function init(){
		parent::init();

		$staff_field = $this->hasOne('Staff','staff_id')->editable(false);
		if($this->api->auth->model) $staff_field->defaultValue($this->api->auth->model->id);
		$this->hasOne('adds/Ad','ad_id');
		$this->addField('amount');
		$this->addField('received_on')->type('date')->defaultValue(date('Y-m-d H:i:s'));

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}