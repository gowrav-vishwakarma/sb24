<?php

class page_event_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$model=$this->api->auth->model->ref('event/Registration');
		if($_GET['info']){
			$model->load($_GET['info']);
			$this->js()->univ()->frameURL('More About This Event',$this->api->url('event_page_more',array('event_id'=>$model['event_id'])))->execute();
		}


		$this->add('H3')->set('Your Registered Events');

		$grid = $this->add('Grid');
		$model->addExpression('event_date')->set(function($m,$q){
			return $m->refSQL('event_id')->fieldQuery('event_date');
		});
		$grid->setModel($model);

		$grid->addColumn('Button','info');
		$grid->addColumn('Confirm','cancle');

		if($_GET['cancle']){
			$registration=$this->add('event/Model_Registration')->load($_GET['cancle']);
			$registration->delete();
			$grid->js(null,$grid->js()->univ()->successMessage("You Have successfully Canceld your registration"))->reload()->execute();
		}

	}
}