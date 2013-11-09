<?php

class page_jobandvacancy_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');

		$manage_job_and_vacancy_segment_tab=$tabs->addTab('Manage Segments');
		$manage_job_and_vacancy_tab=$tabs->addTab('Manage Job And Vacancy');
		$manage_job_and_vacancy_crud=$manage_job_and_vacancy_tab->add('CRUD');
		$manage_job_and_vacancy_crud->add('misc/Export');
		$manage_job_and_vacancy_crud->setModel('jobandvacancy/Listing');
		$manage_job_and_vacancy_crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id')));

		$tabs->addtabURL('jobandvacancy/page_admin_report','Report');

		$manage_job_and_vacancy_segment_crud=
		$manage_job_and_vacancy_segment_tab->add('CRUD');
		$manage_job_and_vacancy_segment_crud->setModel('jobandvacancy/Segment');
	}
}