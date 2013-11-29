<?php
class page_memberhelper_page_member_index extends page_memberpanel_page_base {
	function initMainPage(){
		// parent::init();
		$crud=$this->add('CRUD',array('allow_edit'=>false));
		$model=$this->api->auth->model->ref('memberhelper/MemberHelper');
		$model->getElement('created_at')->system(true);
		$model->getElement('status')->system(true);
		$crud->setModel($model);
		// $mcrud=$crud->addRef('memberhelper/Messages');
		// if($mcrud and $mcrud->isEditing('add')){
		// 	// $mcrud->form->getElement('from')->();
		// }
		if($blg=$crud->grid){
			$blg->js(true)->_load('footable')->_selector('#'.$blg->name.' table')->footable();
			$this->api->jquery->addStyleSheet('footable.core');
			$blg->columns['document_1']['thparam']='data-hide="all"';
			$blg->columns['document_2']['thparam']='data-hide="all"';
			$blg->columns['document_3']['thparam']='data-hide="all"';
			$blg->addColumn('expander','messges');
			
		}
	}

	function page_messges(){
		$this->skip_decoratives = true;
		$this->api->stickyGET('member_helper_id');
		$memberhelper=$this->add('memberhelper/Model_MemberHelper');
		$memberhelper->load($_GET['member_helper_id']);
		$crud=$this->add('CRUD');
		$crud->setMOdel($memberhelper->ref('memberhelper/Messages'));
	}
}