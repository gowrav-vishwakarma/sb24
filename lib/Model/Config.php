<?php
class Model_Config extends Model_Table {
	var $table= "config";
	function init(){
		parent::init();

		$this->add("filestore/Field_Image","front_image_id")->type('image');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}