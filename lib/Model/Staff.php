<?php
class Model_Staff extends Model_Member {
	function init(){
		parent::init();

		$this->addCondition('is_staff',true);
		$this->add('dynamic_model/Controller_AutoCreator');

	}
}