<?php

class Model_History_Place extends Model_Table {
	var $table= "place";
	function init(){
		parent::init();

		$this->hasOne('State','state_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('City','city_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('Area','area_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->hasOne('History_PlaceType','placetype_id')->display(array('form'=>'autocomplete/PlusCrud'))->mandatory(true);
		$this->addField('name');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}