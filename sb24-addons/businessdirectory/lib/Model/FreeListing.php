<?php

namespace businessdirectory;

class Model_FreeListing extends Model_Listing{
	function init(){
		parent::init();

		$this->getElement('state_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('city_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('area_id')->display(array('form'=>'autocomplete/Basic'));
		$this->getElement('image1')->destroy();
		$this->getElement('image2')->destroy();
		$this->getElement('image3')->destroy();
		$this->getElement('image4')->destroy();
		$this->getElement('image5')->destroy();
		$this->getElement('about_us')->system(true);
		$this->getElement('created_on')->system(true);
		$this->getElement('valid_till')->system(true);
		$this->getElement('renewed_on')->system(true);

		$this->addCondition('is_paid',false);
	}
}