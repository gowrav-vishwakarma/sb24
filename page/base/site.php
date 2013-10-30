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
					->addMenuItem($this->api->url('history_page_search',array('reset'=>1)),'History')
					->addMenuItem($this->api->url('businessdirectory_page_search',array('reset'=>1)),'Business Directory')
					->addMenuItem($this->api->url('socialdirectory_page_search',array('reset'=>1)),'Social Directory')
					->addMenuItem($this->api->url('distance_page_search',array('reset'=>1)),'Distance')
					->addMenuItem($this->api->url('tracker_page_search',array('reset'=>1)),'Tracker')
					->addMenuItem($this->api->url('emergency_page_search',array('reset'=>1)),'Emergency Numbers')
					->addMenuItem($this->api->url('event_page_search',array('reset'=>1)),'Events')
					->addMenuItem($this->api->url('jobandvacancy_page_search',array('reset'=>1)),'Jobs & Vacancy')
					->addMenuItem($this->api->url('blooddoner_page_serach',array('reset'=>1)),'Blood Doner')
					->addMenuItem($this->api->url('salesandpurchase_page_search',array('reset'=>1)),'Sales & Purchase')
					->addMenuItem($this->api->url('memberpanel_page_dashboard',array('reset'=>1)),'My Account')
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