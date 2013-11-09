<?php

namespace tracker;

class Model_PINCODEListing extends \Model_Table {
	var $table= "pincode_Listing";
	function init(){
		parent::init();

		$this->hasOne('tracker/PINCODEState','state_id');
		$this->hasOne('tracker/PINCODEDistrict','district_id');

		$this->addField('post_office');
		$this->addField('pin_code');
		$this->addField('search_string')->system(true);

		$this->_dsql()->order('post_office','asc');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$this['search_string']= $this->ref('state_id')->get('name') . " ".
								$this->ref('district_id')->get('name'). " ".
								$this['post_office']. " ".
								$this['pin_code']
							;

		if($this['state_id']=="")
			throw $this->exception('State cannot be empty','ValidityCheck')->setField('state_id');
		if($this['district_id']=="")
			throw $this->exception('City cannot be empty','ValidityCheck')->setField('city_id');

	}
}