<?php

namespace history;

class page_manager extends \page_base_admin {
	function init(){
		parent::init();

		$this->add('Menu')
			->addMenuItem($this->api->url('addonpage',array("addon_page"=>'history\addplaces')),'Create your History Places');


		
}
} 