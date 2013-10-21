<?php

class Model_Member extends Model_Table {
	var $table= "member";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('username')->mandatory('username is must');
		$this->addField('password');
		$this->addField('mobile_no')->hint("Your Password / Activation Codes will be send to this number, Please keep it correct")->mandatory('mobile number is must, your password will be send to this number');

		$this->addField('is_staff')->type('boolean')->defaultValue(false)->system(true);


		$this->addField('update_code')->system(true);
		$this->addField('code_valid_till')->system(true);
		$this->addField('is_active')->type('boolean')->defaultValue(true)->system(true);

		$this->hasMany('businessdirectory/Listing','member_id');
		$this->hasMany('businessdirectory/FreeListing','member_id');
		$this->hasMany('blooddoner/Listing','member_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('afterInsert',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if(!$this->loaded()){
			$this['password']=rand(100,999);
			// check for existing username
			$member=$this->add('Model_Member');
			$member->addCondition('username',$this['username']);
			$member->tryLoadAny();
			if($member->loaded())
				throw $this->exception('This Username is already take, please choose another one','ValidityCheck')->setField('username');
		}
	}

	function afterInsert($model,$new_id){
		$this->add('Controller_SMS')->sendActivationCode($model,$model['password']);
	}

	function sendCode($on_number=null){
		$this['update_code']="SB24-".rand(10000,99999);
		$this['code_valid_till']=date("Y-m-d",strtotime("+1 day"));
		$this->save();

		if($on_number)
			$no=$on_number;
		else
			$no=$this['mobile_no'];
		$this->add('Controller_SMS')->sendSMS($no, $msg="Hi");

	}
}