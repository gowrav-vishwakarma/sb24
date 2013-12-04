<?php
namespace memberhelper;
class Model_Messages extends \Model_Table {
	var $table= "message";
	function init(){
		parent::init();
		$this->hasOne('memberhelper/MemberHelper','memberhelper_id');
		$this->addField('name')->caption('Message')->type('text');
		$this->addField('message_from')->caption('Message From')->system(true);
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d:h:i:s'));

		$this->addHook('beforeSave',$this);

		$this->addExpression('form_message')->set('message_from');
		$this->add('dynamic_model/Controller_AutoCreator');


	}

	function beforeSave(){
		$this['message_from']=$this->api->auth->model['name'];
		// throw new \Exception("Error Processing Request". $this->api->auth->model['name']);
		
	}
}