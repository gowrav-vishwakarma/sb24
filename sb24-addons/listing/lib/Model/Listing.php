<?php
namespace listing;
class Model_Listing extends \Model_Table {
	var $table= "listing";
	function init(){
		parent::init();

		$this->hasOne('Category','category_id');
		$this->hasOne('SubCategory','subcategory_id');
		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');
		$this->hasOne('Area','area_id');


		$this->addField('name');
		$this->addField('about_short');
		$this->addField('description');
		$this->addField('mobile_nos');
		$this->addField('website');
		$this->addField('address');
		$this->addField('email_id');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}