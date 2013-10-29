<?php

namespace event;

class Model_Listing extends \Model_Table {
	var $table= "event_listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('event/Type','type_id');

		$this->addField('name');
		$this->addField('event_date')->type('date');
		$this->addField('event_venue');
		$this->addField('about_event')->type('text')->display(array('grid'=>'shorttext','form'=>'RichText'));
		$this->addField('is_registrable')->type('boolean')->defaultValue(true);
		$this->addField('registration_last_date')->type('date');

		$this->add("filestore/Field_Image","event_picture_id")->type('image');

		$this->hasMany('event/Registration','event_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function register($member){

		if(!$this->loaded()) throw $this->exception('Event must be loaded before Registration');

		if(is_numeric($member)){
			$member=$this->add('Model_Member')->tryLoad($member);
			if(!$member->loaded()) throw $this->exception('Member Not Found');
		}

		$register = $this->add('event/Registration');
		$register->addCondition('member_id',$member->id);
		$register->addCondition('event_id',$this->id);
		$register->tryLoadAny();

		if($register->loaded())
			$this->api->js()->univ()->errorMessage('Member Already Registered')->execute();
		$register->save();

	}
}