<?php
namespace salesandpurchase;
class Model_SubCategory extends \Model_Table {
	var $table= "sales_subcategory";
	function init(){
		parent::init();

		$this->hasOne('salesandpurchase/Category','category_id');
		$this->addField('name');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}