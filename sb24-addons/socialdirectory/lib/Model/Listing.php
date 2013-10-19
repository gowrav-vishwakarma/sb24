<?php

namespace socialdirectory;

class Model_Listing extends \Model_Table {
	var $table= "social_listing";
	function init(){
		parent::init();

		$this->hasOne('socialdirectory/Religion','religion_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('socialdirectory/Cast','cast_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('socialdirectory/SubCast','subcast_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('Tehsil','tehsil_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/Plus'));
		$this->addField('occupation');
		$this->addField('name')->mandatory('This is Must');
		$this->addField('father_name');
		$this->addField('pincode');
		$this->addField('ph_no')->mandatory('This is Must');
		$this->addField('email_id')->mandatory('This is Must');
		$this->addField('created_on')->type('date')->defaultVAlue(date('Y-m-d'));
		$this->addField('valid_till')->type('date');
		$this->addField('renewed_on')->type('date');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}