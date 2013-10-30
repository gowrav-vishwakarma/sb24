<?php

class page_jobandvacancy_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('View_ModuleHeading');//->set('Find Job & Vacancy')->sub('Search via State, City, Minimum Package');
		$form=$this->add('Form');
		$segment_field=$form->addField('dropdown','segment')->setEmptyText("Please Select Any");
		$segment_field->setModel('jobandvacancy/Segment');
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Please Select Any");
		$state_field->setModel('State');
		$city_field=$form->addField('dropdown','city_id','City')->setEmptyText("Please Select Any");
		$city_field->setModel('City');
		$tehsil_field=$form->addField('dropdown','tehsil_id','Tehsil')->setEmptyText("Please Select Any");
		$tehsil_field->setModel('Tehsil');
		$form->addField('Number','min_package')->setFieldHint('Package/Annum ie 250000');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span3'),'after','state_id')
                ->move($form->addSeparator('noborder span4'),'after','tehsil_id')
                ->now();
                $form->add('Button')->set('Filter Search')->addStyle(array('margin-top'=>'25px','height'=>'30px'))->addClass('atk-form-row atk-form-row-dropdown span10')->js('click')->submit();

		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id'),'force_selection'=>true));
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
			if($_GET['tehsil'])
				$model_jobandvacancy->addCondition('tehsil_id',$_GET['tehsil']);
			if($_GET['min_package'])
				$model_jobandvacancy->addCondition('min_package','>=',$_GET['min_package']);
		}


		

		$v->setModel($model_jobandvacancy);
	

		if($form->isSubmitted()){

			$v->js()->reload(array('segment'=>$form->get('segment'),
									'state'=>$form->get('state_id'),
									'city'=>$form->get('city_id'),
									'tehsil'=>$form->get('tehsil_id'),
									'min_package'=>$form->get('min_package'),
									'filter'=>'1'))->execute();
		}
	}
}