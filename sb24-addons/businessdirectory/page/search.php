<?php

class page_businessdirectory_page_search extends page_base_site {
	function init(){
		parent::init();
		
		$this->api->add('View',null,'advertising')->set("HEIE");

	}
}