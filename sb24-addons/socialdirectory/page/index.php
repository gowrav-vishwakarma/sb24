<?php

class page_socialdirectory_page_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');

		$religion_tab=$tabs->addTab('Manage Religions');
		$cast_tab=$tabs->addTab('Manage Cast');
		$manage_social_tab=$tabs->addTab('Manage Social Directory');
		$report_tab=$tabs->addtabURL('socialdirectory/page_report','Reports');
		
		$religion_crud=$religion_tab->add('CRUD');
		$religion_crud->setModel('socialdirectory/Religion');

		$cast_crud=$cast_tab->add('CRUD');
		$cast_crud->setModel('socialdirectory/Cast');
		$cast_crud->addRef('socialdirectory/SubCast');


		$manage_crud=$manage_social_tab->add('CRUD');
		$manage_crud->setModel('socialdirectory/Listing');
		if($manage_crud->grid)
			$manage_crud->grid->addQuickSearch(array('name','cast'));
	}
}