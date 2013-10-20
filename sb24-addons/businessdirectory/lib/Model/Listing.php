<?php

namespace businessdirectory;

class Model_Listing extends \Model_Table {
	var $table= "business_listing";
	function init(){
		parent::init();

		$member = $this->hasOne('Member','member_id');
		if($this->api->auth->model) $member->defaultValue($this->api->auth->model->id);

		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/Plus'));
		$this->addField('name')->caption('Name Of Company');
		$this->addField('company_address')->type('text');
		$this->addField('mobile_no');
		$this->addField('company_ph_no');
		$this->addField('type_of_work')->type('text');
		$this->add("filestore/Field_Image","company_logo_id")->type('image');
		$this->addField('email_id');
		$this->addField('website');
		$this->addField('contact_person');
		$this->addField('designation')->setValueList(array(	
															'proprietor'=>'Proprietor',
															'partner'=>'Partner',
															'director'=>'Director',
															'authorized-person'=>'Authorized-Person'
															));
		$this->addField('contact_number');
		$this->addField('address')->type('text');
		$this->addField('is_paid')->type('boolean');
		$this->add("filestore/Field_Image","image1")->type('image');
		$this->add("filestore/Field_Image","image2")->type('image');
		$this->add("filestore/Field_Image","image3")->type('image');
		$this->add("filestore/Field_Image","image4")->type('image');
		$this->add("filestore/Field_Image","image5")->type('image');
		$this->addField('about_us')->type('text');
		$this->addField('created_on')->type('date')->defaultVAlue(date('Y-m-d'));
		$this->addField('valid_till')->type('date');
		$this->addField('renewed_on')->type('date');


		$this->hasMany('businessdirectory/RegisteredCategory','listing_id');
		// $this->hasOne('RegisteredCategory','category_id');
		// $this->hasOne('SubCategory','subcategory_id')->display(array('form'=>'autocomplete/Plus'));
		// $this->add('Controller_MobileActivation');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}