<?php

namespace businessdirectory;

class Model_FreeListing extends Model_Listing{
	function init(){
		parent::init();

		$this->getElement('state_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('city_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('area_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('company_logo')->system(true);

		$this->addCondition('is_paid',false);
	}
}