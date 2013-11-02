<?php
namespace businessdirectory;
class Model_ProductImages extends \Model_Table {
	var $table= "businessdirectory_product_images";
	function init(){
		parent::init();

		$this->hasOne('businessdirectory/Listing','listing_id');
		$this->add('filestore/Field_Image','product_img_id')->type('image');
		$this->addField('name');
		$this->addField('info')->type('text');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}