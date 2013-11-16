<?php	
class page_memberhelper_page_admin_index extends page_base_admin {
	function init(){
		parent::init();
		$crud=$this->add('CRUD',array('allow_add'=>false));
		$crud->setModel('memberhelper/MemberHelper');
	}
}