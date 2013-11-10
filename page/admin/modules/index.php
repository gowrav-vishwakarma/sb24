<?php

class page_admin_modules_index extends page_base_admin {
	function init(){
		parent::init();

		$modules = array(
				'history/page_admin_index' => 'History',
				'socialdirectory/page_admin_index' => 'Social Directory',
				'businessdirectory/page_admin_index' => 'Business Directory',
				'jobandvacancy/page_admin_index' => 'Job And Vacancy Directory',
				'distance/page_admin_index' => 'Distances',
				'tracker/page_admin_index' => 'Tracker',
				'emergency/page_admin_index' => 'Emergency',
				'event/page_admin_index' => 'Events',
				'blooddoner/page_admin_index' => 'Blood Doner',
				'salesandpurchase/page_admin_index' => 'Sales & Purchase',
				'memberhelper/page_admin_index' => 'Public Helper'
			);

		$tabs=$this->add('Tabs');
		foreach($modules as $page=>$title){
			$tabs->addTabURL($page,$title);
		}

	}
}