<?php
class Model_Directory_Core extends Model_Table {
	var $table= "directory";
	function init(){
		parent::init();

		$this->hasOne('Directory_Religion','religion_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('Directory_Cast','cast_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('Directory_SubCast','subcast_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/Plus'))->mandatory('This is Must');
		$this->hasOne('Tehsil','tehsil_id')->display(array('form'=>'autocomplete/Plus'));
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/Plus'));
		$this->addField('occupation');
		$this->addField('name')->mandatory('This is Must');
		$this->addField('father_name');
		$this->addField('pincode');
		$this->addField('ph_no')->mandatory('This is Must');
		$this->addField('email_id')->mandatory('This is Must');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}