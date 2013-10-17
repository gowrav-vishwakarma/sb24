<?php
class Model_Directory_SubCast extends Model_Table {
	var $table= "subcast";
	function init(){
		parent::init();

		$this->hasOne('Directory_Cast','cast_id');
		$this->addField('name')->Caption('Sub Cast');
		$this->hasMany('Directory_Core','subcast_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}