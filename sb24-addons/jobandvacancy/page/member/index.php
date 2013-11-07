<?php

class page_jobandvacancy_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$job_tab=$tabs->addTab('Create Job Application');
		$vacancy_tab=$tabs->addTab('Create Vacancy');
		$crud=$job_tab->add('CRUD');
		$job=$this->add('jobandvacancy/Model_MemberListing');
		$job->addCondition('posting_type','job');
		$crud->setModel($job,array('state_id','city_id','tehsil_id','apply_post','min_experience','min_package',
									'max_package','contact_person','contact_number','address','email_id','description'),array('city','contact_person','contact_number','email_id','address'));
		$crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id')));

		if($crud->grid)
			$crud->add_button->set('Post Your Job');
		if($crud->form){
			$crud->form->template->tryDel('button_row');
			 $crud->form->add('Button',null,null,array('view/mybutton','button'))->set('Save')->addStyle(array('margin-top'=>'25px','margin-left'=>'390px'))->addClass('shine1')->js('click')->submit();
			
		}
	

	$vacancy_crud=$vacancy_tab->add('CRUD');
	$vacancy=$this->add('jobandvacancy/Model_MemberListing');
	$vacancy->addCondition('posting_type','vacancy');
		if($vacancy_crud->form)
			$vacancy->getElement('apply_post')->system(true);
	$vacancy_crud->setModel($vacancy,null,'base');
	$vacancy_crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id')));

		if($vacancy_crud->grid)
			$vacancy_crud->add_button->set('Post Your Vacancy');
		if($vacancy_crud->form){
			$vacancy_crud->form->template->tryDel('button_row');
			 $vacancy_crud->form->add('Button',null,null,array('view/mybutton','button'))->set('Save')->addStyle(array('margin-top'=>'25px','margin-left'=>'390px'))->addClass('shine1')->js('click')->submit();
			
		}
	
	}
}