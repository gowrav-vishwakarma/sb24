<?php
class page_base_site extends page_base_null {
	function init(){
		parent::init();
		$this->setUpSiteMenus();
		$this->setUpAdds();
	}

	function setUpSiteMenus(){
		$this->api->menu
					->addMenuItem('index','Home')
					->addMenuItem('history_page_search','History')
					->addMenuItem('businessdirectory_page_search','Business Directory')
					->addMenuItem('socialdirectory_page_search','Social Directory')
					->addMenuItem('distance_page_search','Distance')
					->addMenuItem('tracker_page_search','Tracker')
					->addMenuItem('emergency_page_search','Emergency Numbers')
					->addMenuItem('event_page_search','Events')
					->addMenuItem('jobandvacancy_page_search','Jobs & Vacancy')
					->addMenuItem('blooddoner_page_serach','Blood Doner')
					->addMenuItem('salesandpurchase_page_search','Sales & Purchase')
					->addMenuItem('memberpanel_page_dashboard','My Account')
					;
	}

	function setUpAdds(){
		$top_blocks = $this->add('adds/Model_AdBlock')
						->addCondition('position','Top')
						->addCondition('is_active',true);
		foreach($top_blocks as $junk){
			$temp_block = $this->add('adds/Model_AdBlock')->load($top_blocks->id);
			$v=$this->api->add('adds/View_AdBlock',null,'top_advert_spot');
			$v->setModel($temp_block);
		}

		$left_blocks = $this->add('adds/Model_AdBlock')
						->addCondition('position','Left')
						->addCondition('is_active',true);
		foreach($left_blocks as $junk){
			$temp_block = $this->add('adds/Model_AdBlock')->load($left_blocks->id);
			$v=$this->api->add('adds/View_AdBlock',null,'left');
			$v->setModel($temp_block);
		}

		$right_blocks = $this->add('adds/Model_AdBlock')
						->addCondition('position','Right')
						->addCondition('is_active',true);
		foreach($right_blocks as $junk){
			$temp_block = $this->add('adds/Model_AdBlock')->load($right_blocks->id);
			$v=$this->api->add('adds/View_AdBlock',null,'right');
			$v->setModel($temp_block);
		}
	}
}