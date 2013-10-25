<?php

class page_event_page_more extends page_base_site {
	
	function init(){
		parent::init();

		$event=$this->add('event/Model_Listing')->load($_GET['event_id']);

		$this->add('View_Error')->set('Under Construction');
		$this->add('View')->set($event['name']);

	}
}