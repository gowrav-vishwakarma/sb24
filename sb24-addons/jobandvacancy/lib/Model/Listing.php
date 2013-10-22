<?php

namespace jobandvacancy;

class Model_Listing extends \Model_Table {
	var $table= "jobandvacancy_listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('jobandvacancy/Segment','segment_id');
		$this->addField('name')->caption('Title');
		$this->addField('company_name');
		$this->addField('post');
		$this->addField('no_of_post');
		$this->addField('min_experience');
		$this->addField('min_package')->type('int');
		$this->addField('max_package')->type('int');
		$this->addField('contact_person');
		$this->addField('contact_number');
		$this->addField('address');
		$this->addField('created_on')->type('date');
		$this->addField('valid_till')->type('date');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}