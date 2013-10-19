<?php
class page_base_site extends page_base_null {
	function init(){
		parent::init();
		$this->setUpSiteMenus();
	}

	function setUpSiteMenus(){
		$this->api->menu
					->addMenuItem('index','Home')
					->addMenuItem('businessdirectory_page_freelisting','Free Listing')
					->addMenuItem('businessdirectory_page_search','Business Directory')
					->addMenuItem('socialdirectory_page_search','Social Directory')
					->addMenuItem('distance_page_search','Distance')
					->addMenuItem('tracker_page_search','Tracker')
					->addMenuItem('emergency_page_search','Emergency Numbers')
					->addMenuItem('maps_page_search','Maps')
					->addMenuItem('jobandvacancy_page_search','Jobs & Vacancy')
					->addMenuItem('blooddoner_page_serach','Blood Doner')
					->addMenuItem('salesandpurchase_page_search','Sales & Purchase')
					->addMenuItem('memberpanel_page_dashboard','My Account')
					;
	}
}