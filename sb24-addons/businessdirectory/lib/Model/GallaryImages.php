<?php
namespace businessdirectory;
class Model_GallaryImages extends \Model_Table {
	var $table= "businessdirectory_gallary_images";
	function init(){
		parent::init();

		$this->hasOne('businessdirectory/Listing','listing_id');
		$this->add('filestore/Field_Image','gallary_img_id')->type('image');
		$this->addField('name');
		$this->addField('info')->type('text');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}