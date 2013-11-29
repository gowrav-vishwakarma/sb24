<?php
class page_admin_master extends page_base_admin{
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');


		$state_tab=$tabs->addTab('States');
		$state_crud=$state_tab->add('CRUD');
		$state_crud->setModel('State');
		if($sg=$state_crud->grid) $sg->addQuickSearch(array('name'));

		$city_tabs=$tabs->addTab('City');
		$city_crud=$city_tabs->add('CRUD');
		$city_crud->setModel('City');
		if($cg=$city_crud->grid){
			$cg->addPaginator(20);
			$cg->addQuickSearch(array('state','name'));	
		} 


		$tehsil_tabs=$tabs->addTab('Tehsil');
		$tehsil_crud=$tehsil_tabs->add('CRUD');
		$tehsil_crud->setModel('Tehsil');
		if($tg=$tehsil_crud->grid){
			$tg->addQuickSearch(array('state','city','name'));
			$tg->addPaginator(20);
		}

		$area_tabs=$tabs->addTab('Area');
		$area_crud=$area_tabs->add('CRUD');
		$area_crud->setModel('Area');
		$area_crud->add('Controller_ChainSelector',array('chain_fields'=>array('tehsil_id'=>'city_id')));
		if($ag=$area_crud->grid){
			$ag->addQuickSearch(array('state','city','tehsil','name'));
			$ag->addPaginator(20);
		}

		$question_tabs=$tabs->addTab('Question');
		$question_crud=$question_tabs->add('CRUD');
		$question_crud->setModel('Questions');
		$contact_tab=$tabs->addTab('Feedback Info');
		$feedback_grid=$contact_tab->add('Grid');
		$feedback_grid->setModel('Contact');

	}
}