<?php
class Model_OldListing extends Model_Table {
	var $table= "old_businesslisting";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('authority');
		$this->addField('contactno');
		$this->addField('address');
		$this->addField('companyname');
		$this->addField('category');
		$this->addField('subcategory');
		$this->addField('state');
		$this->addField('city');
		$this->addField('tehsil');
		$this->addField('area');
		$this->addField('companyaddress');
		$this->addField('typeofwork');
		$this->addField('mobileno');
		$this->addField('phno1');
		$this->addField('phno2');
		$this->addField('emailid');
		$this->addField('website');
		$this->addField('date');
		$this->addField('amount');

	}
}
