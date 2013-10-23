<?php

class page_jobandvacancy_page_search extends page_base_site {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$segment_field=$form->addField('dropdown','segment')->setEmptyText("Please Select Any");
		$segment_field->setModel('jobandvacancy/Segment');
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Please Select Any");
		$state_field->setModel('State');
		$city_field=$form->addField('dropdown','city_id','City')->setEmptyText("Please Select Any");
		$city_field->setModel('City');
		$form->addField('Number','min_package')->setFieldHint('Package/Annum ie 250000');
		$form->addSubmit('Search');

		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id'),'force_selection'=>true));
		}

		$v=$this->add('jobandvacancy/View_Listing');
		$model_jobandvacancy=$this->add('jobandvacancy/Model_Listing');
		$model_jobandvacancy->addCondition('is_active',true);
		if($_GET['filter']){
			if($_GET['segment'])
				$model_jobandvacancy->addCondition('segment_id',$_GET['segment']);
			if($_GET['state'])
				$model_jobandvacancy->addCondition('state_id',$_GET['state']);
			if($_GET['city'])
				$model_jobandvacancy->addCondition('city_id',$_GET['city']);
			if($_GET['min_package'])
				$model_jobandvacancy->addCondition('min_package','>=',$_GET['min_package']);
		}

		$v->setModel($model_jobandvacancy);

		if($form->isSubmitted()){
			$v->js()->reload(array('segment'=>$form->get('segment'),
									'state'=>$form->get('state'),
									'city'=>$form->get('city'),
									'min_package'=>$form->get('min_package'),
									'filter'=>'1'))->execute();
		}
	}
}