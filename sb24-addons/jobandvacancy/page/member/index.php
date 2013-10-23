<?php

class page_jobandvacancy_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$crud->setModel('jobandvacancy/Model_MemberListing',null,'base');
		$crud->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id')));

		if($crud->grid)
			$crud->add_button->set('Post Your Job');

	}
}