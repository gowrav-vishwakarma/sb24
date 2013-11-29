<?php	
class page_memberhelper_page_admin_index extends page_base_admin {
	function initMainPage(){
		// parent::init();
		$crud=$this->add('CRUD',array('allow_add'=>false));
		$crud->setModel('memberhelper/MemberHelper');

		if($crud->grid)
			$crud->grid->addColumn('expander','messages');		
	}

	function page_messages(){
		$this->skip_decoratives = true;
		$this->api->stickyGET('member_helper_id');
		$memberhelper=$this->add('memberhelper/Model_MemberHelper');
		$memberhelper->load($_GET['member_helper_id']);
		$crud=$this->add('CRUD');
		$crud->setMOdel($memberhelper->ref('memberhelper/Messages'));
	}
}