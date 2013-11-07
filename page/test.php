<?php
class page_test extends Page {
	function init(){
		parent::init();
		$this->add('Controller_SMS')->sendSMS('9783807100','GVS','1234');

	}
}
