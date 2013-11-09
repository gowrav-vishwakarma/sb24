<?php

class page_event_page_more extends page_base_site {
	
	function init(){
		parent::init();

		$event=$this->add('event/Model_Listing')->load($_GET['event_id']);

		$this->add('H3')->setHTML("About ". $event['name']);
		$this->add('View')->setHTML($event['about_event']);

	}
}