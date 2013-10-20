<?php

class page_admin_modules_index extends page_base_admin {
	function init(){
		parent::init();

		$modules = array(
				'history/page_index' => 'History',
				'socialdirectory/page_admin_index' => 'Social Directory',
				'businessdirectory/page_admin_index' => 'Business Directory',
				'jobandvacancy/page_admin_index' => 'Job And Vacancy Directory',
				'distance/page_admin_index' => 'Distances'
			);

		$tabs=$this->add('Tabs');
		foreach($modules as $page=>$title){
			$tabs->addTabURL($page,$title);
		}

	}
}