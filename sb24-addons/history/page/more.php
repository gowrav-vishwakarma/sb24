<?php

class page_history_page_more extends page_base_site{
	function init(){
		parent::init();

		$tab=$this->add('Tabs');
		$t1=$tab->addTab('About Us');
		$t2=$tab->addTab('Place Images');
		$t3=$tab->addTab('Video');
		$t4=$tab->addTab('Audio');

		$history=$this->add('history/Model_Place')->load($_GET['place_id']);
		$this->add('H3')->set("About ". $history['name']);
		$this->add('View')->setHTML($history['about']);


	}
}