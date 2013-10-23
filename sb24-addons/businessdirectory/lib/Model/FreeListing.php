<?php

namespace businessdirectory;

class Model_FreeListing extends Model_Listing{
	function init(){
		parent::init();

		$this->getElement('category_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('state_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('city_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('area_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('created_on')->system(true);
		$this->getElement('valid_till')->system(true);
		$this->getElement('last_paid_on')->system(true);

		$this->addCondition('is_paid',false);
	}
}