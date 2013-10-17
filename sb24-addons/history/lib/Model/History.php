<?php
namespace history;

class Model_History extends /Model_Table{
	var $table="history";
		function init(){
		parent::init();

		$this->hasOne('Category','category_id');
		$this->hasOne('SubCategory','subcategory_id');
		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');
		$this->hasOne('Area','area_id');

		$this->addField('name')->Caption('Type of Places');


		$this->add('dynamic_model/Controller_AutoCreator');


	}
}