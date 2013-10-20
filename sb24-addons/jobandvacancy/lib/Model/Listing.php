<?php

namespace jobandvacancy;

class Model_Listing extends \Model_Table {
	var $table= "jobandvacancy_listing";
	function init(){
		parent::init();

		$this->hasOne('Category','category_id')->display(array('form'=>'autocomplete/PlusCrud'));
		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/PlusCrud'));
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/PlusCrud'));
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/PlusCrud'));
		$this->addField('name')->caption('Title');
		$this->addField('company_name');
		$this->addField('contact_person');
		$this->addField('contact_number');
		$this->addField('vacancy');
		$this->addField('created_on')->type('date');
		$this->addField('valid_till')->type('date');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}