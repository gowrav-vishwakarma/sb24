<?php

class page_businessdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$mange_buisness_tab=$tabs->addTab('Manage Business Directory');
		$mange_buisness_crud=$mange_buisness_tab->add('CRUD');
		$mange_buisness_crud->setModel('businessdirectory/Listing');
		
		$tabs->addtabURL('businessdirectory/page_admin_report','Reports');		
	}
}