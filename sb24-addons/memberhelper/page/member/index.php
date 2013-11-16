<?php
class page_memberhelper_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();
		$crud=$this->add('CRUD',array('allow_edit'=>false));
		$crud->setModel($this->api->auth->model->ref('memberhelper/MemberHelper'));
	}
}