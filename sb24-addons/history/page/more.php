<?php

class page_history_page_more extends page_base_site{
	function init(){
		parent::init();

		$history=$this->add('history/Model_Place')->load($_GET['place_id']);
		$this->add('H3')->set("About ". $history['name']);
		$this->add('View')->setHTML($history['about']);
	}
}