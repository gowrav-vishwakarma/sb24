<?php

class page_socialdirectory_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');

		$manage_social_tab=$tabs->addTab('Manage Social Directory');
		$cast_tab=$tabs->addTab('Manage Cast');
		$religion_tab=$tabs->addTab('Manage Religions');
		// $report_tab=$tabs->addtabURL('socialdirectory/page_admin_report','Reports');
		
		$religion_crud=$religion_tab->add('CRUD');
		$religion_crud->setModel('socialdirectory/Religion');

		$cast_crud=$cast_tab->add('CRUD');
		$cast_crud->setModel('socialdirectory/Cast');
		$cast_crud->addRef('socialdirectory/SubCast');


		$manage_crud=$manage_social_tab->add('CRUD');
		$manage_crud->setModel('Member');
		if($mg=$manage_crud->grid){
			$mg->addQuickSearch(array('name','username','mobile_no'));
			$mg->addPaginator(20);
		}
		$manage_crud->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id'),'force_selection'=>false));
	}
}