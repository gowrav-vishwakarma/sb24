<?php

class page_jobandvacancy_page_more extends page_base_site{
	function init(){
		parent::init();

		$jobandvacancy=$this->add('jobandvacancy/Model_Listing')->load($_GET['listing_id']);
		$this->add('H3')->set('About Vacancy & Company');
		$this->add('View')->setHTML($jobandvacancy['description']);

	}
}