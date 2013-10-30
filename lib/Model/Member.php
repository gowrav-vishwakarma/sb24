<?php

class Model_Member extends Model_Table {
	var $table= "member";
	function init(){
		parent::init();

		$this->addField('name')->group('base');
		$this->addField('father_name')->group('social');
		$this->addField('username')->mandatory('username is must')->group('base');
		$this->addField('password')->group('base');
		$this->addField('mobile_no')->hint("Your Password / Activation Codes will be send to this number, Please keep it correct")->mandatory('mobile number is must, your password will be send to this number')->group('base');

		$this->hasOne('State','state_id')->group('base');
		$this->hasOne('City','city_id')->group('base');
		$this->addField('address')->type('text')->group('social');
		$this->addField('date_of_birth')->type('date')->group('social');
		$this->addField('gender')->enum(array('Male','Female'))->group('social');
		$this->addField('interest')->type('text')->caption('About You & Your Interest')->display(array('form'=>'RichText'))->group('social');
		
		$this->hasOne('socialdirectory/Religion','religion_id')->group('social');
		$this->hasOne('socialdirectory/Cast','cast_id')->group('social');
		$this->hasOne('socialdirectory/SubCast','subcast_id')->group('social');

		$this->addField('is_staff')->type('boolean')->defaultValue(false)->system(true);
		$this->addField('joined_on')->type('date')->defaultValue(date('Y-m-d H:i:s'))->system(true);


		$this->addField('update_code')->system(true);
		$this->addField('code_valid_till')->system(true);
		$this->addField('is_active')->type('boolean')->defaultValue(true)->system(true);

		$this->addField('search_string')->system(true);

		$this->hasMany('businessdirectory/Listing','member_id');
		$this->hasMany('businessdirectory/FreeListing','member_id');
		$this->hasMany('blooddoner/Listing','member_id');
		$this->hasMany('jobandvacancy/Listing','member_id');
		$this->hasMany('event/Registration','member_id');
		$this->hasMany('salesandpurchase/Listing','member_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('afterInsert',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$this['search_string']= $this['name'].  " ".
								$this['mobile_no'] . " ".
								$this->ref('city_id')->get('name') . " ".
								$this->ref('state_id')->get('name') . " ".
								$this['address'] . " " . 
								$this['father_name'] . " " .
								$this['interest'] . " " 
								;


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
		$this->add('Controller_SMS')->sendCode($model['mobile_no'],$model['username'],$model['password']);
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