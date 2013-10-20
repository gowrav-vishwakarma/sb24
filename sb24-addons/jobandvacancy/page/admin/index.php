<?php

class page_jobandvacancy_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');

		$manage_job_and_vacany_tab=$tabs->addTab('Manage Job And Vacancy');
		$manage_job_and_vacany_crud=$manage_job_and_vacany_tab->add('CRUD');
		$manage_job_and_vacany_crud->setModel('jobandvacancy/Listing');

		$tabs->addtabURL('jobandvacancy/page_admin_report','Report');

			
	}
}