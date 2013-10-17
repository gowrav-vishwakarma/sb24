<?php
class Model_SubCategory extends Model_Table {
	var $table= "sub_category";
	function init(){
		parent::init();

		$this->hasOne('Category','category_id');
		$this->addField('name');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}