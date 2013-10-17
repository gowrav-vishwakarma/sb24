<?php

namespace listing;

class page_manager extends \page_base_admin {
	function init(){
		parent::init();

		$this->add('Menu')->addMenuItem('listing\abcd','abcd');		

	}
}