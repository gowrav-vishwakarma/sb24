<?php

namespace jobandvacancy;

class Model_Listing extends \Model_Table {
	var $table= "jobandvacancy_listing";
	function init(){
		parent::init();

		$member = $this->hasOne('Member','member_id');
			if($this->api->auth->model) 
				$member->defaultValue($this->api->auth->model->id);
		$this->hasOne('State','state_id')->group('all');
		$this->hasOne('City','city_id');//->group('all');
		$this->hasOne('Tehsil','tehsil_id')->group('all');
		$this->hasOne('jobandvacancy/Segment','segment_id')->group('all');
		$this->addField('name')->caption('Post vancant')->group('base');
		$this->addField('apply_post')->caption('Post For Apply')->group('base');
		$this->addField('company_name')->group('all');
		$this->addField('no_of_posts')->group('base');
		$this->addField('min_experience')->group('all');
		$this->addField('min_package')->type('int')->display(array('grid'=>'money'))->group('all');
		$this->addField('max_package')->type('int')->display(array('grid'=>'money'))->group('all');
		$this->addField('contact_person')->group('all');
		$this->addField('contact_number')->group('all');
		$this->addField('address')->group('all');
		$this->addField('email_id')->group('all');
		$this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('all');
		$this->addField('created_on')->type('date')->defaultValue(date('Y-m-d'))->group('base');
		$this->addField('valid_till')->type('date')->group('base');
		$this->addField('is_active')->type('boolean')->defaultValue(false)->group('all');
		$this->addField('posting_type')->defaultValue('vacancy')->enum(array('vacancy','job_application'))->group('all');
		$this->addField('search_string')->system(true);

		$this->add('dynamic_model/Controller_AutoCreator');

		$this->addHook('beforeSave',$this);
	}

	function beforeSave(){
		$this['search_string']= $this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('tehsil_id')->get('name'). " ".
								$this['name']. " ".
								$this["company_address"]. " ".
								$this['address']. " ".
								$this['description']
							;

		if($this['state_id']=="") throw $this->exception('State is Must','ValidityCheck')->setField('state_id')->addMoreInfo('listingName',$this['name']); 
		if($this['city_id']=="") throw $this->exception('City is Must','ValidityCheck')->setField('city_id'); 
		if($this['tehsil_id']=="") throw $this->exception('Tehsil is Must','ValidityCheck')->setField('city_id'); 
	}
}