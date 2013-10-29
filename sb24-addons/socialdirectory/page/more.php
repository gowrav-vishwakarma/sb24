<?php

class page_socialdirectory_page_more extends page_base_site{
	function init(){
		parent::init();

		$listing=$this->add('Model_Member');
		$listing->load($_GET['member_id']);
		$this->add('H4')->set('About '.$listing['name']);
		$this->add('View')->setHTML($listing['interest']);

	}
}