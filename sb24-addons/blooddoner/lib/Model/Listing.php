<?php

namespace blooddoner;

class Model_Listing extends \Model_Table {
	var $table= "blooddoner";
	function init(){
		parent::init();

		$this->hasOne('Member','member_id')->defaultValue($this->api->auth->model->id);
		$this->addField('blood_group')->enum(array('A+','A-','B+','B-','O+','O-','AB+','AB-'));
		$this->addField('want_to_donate')->type('boolean')->defaultValue(false)->caption('I Am Available to donate my blood');
		
		$this->addField('update_code')->system(true);
		$this->addField('code_valid_till')->system(true);
		$this->addField('is_active')->type('boolean')->defaultValue(true)->system(true);


		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

	}

	function sendCode($on_number=null){
		$this['update_code']="BLD-".rand(10000,99999);
		$this['code_valid_till']=date("Y-m-d",strtotime("+1 day"));
		$this->save();

		if($on_number)
			$no=$on_number;
		else
			$no=$this->ref('member_id')->get('mobile_no');
		$this->add('Controller_SMS')->sendSMS($no, $msg="Hi");

	}

}