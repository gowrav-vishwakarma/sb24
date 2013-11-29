<?php
namespace history;
class Model_Images extends Model_Table {
	var $table= "place_images";
	function init(){
		parent::init();
		$this->hasOne('history/Place','place_id');
		$this->addField('filestore/Field_Image','image_id')->type('image');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}