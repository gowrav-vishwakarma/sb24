<?php

class page_history_page_more extends page_base_site{
	function init(){
		parent::init();

		$tab=$this->add('Tabs');
		$t1=$tab->addTab('About Us');
		$t2=$tab->addTab('Place Images');
		$t3=$tab->addTab('Video');
		$t4=$tab->addTab('Audio');

		$history=$t1->add('history/Model_Place')->load($_GET['place_id']);
		$t1->add('H3')->set("About ". $history['name']);
		$t1->add('View')->setHTML($history['about']);

		$t2->add('H3')->setHTML('Images');

		
		$t3->add('H3')->setHTML('Video');
		$t4->add('H3')->setHTML('Audio');


	}
}