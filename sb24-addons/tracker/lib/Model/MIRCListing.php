<?php

namespace tracker;

class Model_MIRCListing extends \Model_Table {
	var $table= "mirc_Listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('tracker/Bank','bank_id')->display(array('form'=>'autocomplete/Plus'));

		$this->addField('branch');
		$this->addField('mirc');
		$this->addField('ifsc');
		$this->addField('search_string');

		$this->addHook('beforeSave',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$this['search_string']=
								$this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('bank_id')->get('name'). " ".
								$this["branch"]. " ".
								$this['mirc']. " ".
								$this["ifsc"]
							;
		if($this['state_id']=="")
			throw $this->exception('State cannot be empty','ValidityCheck')->setField('state_id');
		if($this['city_id']=="")
			throw $this->exception('City cannot be empty','ValidityCheck')->setField('city_id');

	}
}