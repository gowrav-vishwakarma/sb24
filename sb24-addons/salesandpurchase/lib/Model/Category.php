<?php
namespace salesandpurchase;
class Model_Category extends \Model_Table {
	var $table= "sales_category";
	function init(){
		parent::init();

		$this->addField('name')->caption('Category Name');
		$this->hasMany('salesandpurchase/SubCategory','category_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}