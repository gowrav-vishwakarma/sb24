<?php

class Model_Member extends Model_Table {
	var $table= "member";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('username')->mandatory('username is must');
		$this->addField('password');
		$this->addField('mobile_no')->hint("Your Password will be send to this number")->mandatory('mobile number is must, your password will be send to this number');

		$this->hasMany('businessdirectory/Listing','member_id');
		$this->hasMany('businessdirectory/FreeListing','member_id');

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
}