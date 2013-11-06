<?php

class page_jobandvacancy_page_search extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('segment');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('min_package');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("segment",$_GET['segment']?:$this->recall('segment',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("min_package",$_GET['min_package']?:$this->recall('min_package',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('View_ModuleHeading')->set('Find Job & Vacancy')->sub('Search via State, City, Minimum Package');
		$form=$this->add('Form');
		$segment_field=$form->addField('dropdown','segment')->setEmptyText("Please Select Segment");
		$segment_field->setModel('jobandvacancy/Segment');
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Please Select State");
		$state_field->setModel('State');
		$city_field=$form->addField('dropdown','city_id','City')->setEmptyText("Please Select City");
		$city_field->setModel('City');
		$tehsil_field=$form->addField('dropdown','tehsil_id','Tehsil')->setEmptyText("Please Select Tehsil");
		$tehsil_field->setModel('Tehsil');
		$form->addField('Number','min_package')->setFieldHint('Package/Annum ie 250000');
		$form->addField('line','search');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span3'),'after','state_id')
                ->move($form->addSeparator('noborder span4'),'after','tehsil_id')
                ->now();
                $form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'25px'))->addClass(' shine1')->js('click')->submit();

		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id'),'force_selection'=>true));
		}

		$v=$this->add('jobandvacancy/View_Listing');
		$model_jobandvacancy=$this->add('jobandvacancy/Model_Listing');
		$model_jobandvacancy->addCondition('is_active',true);
		if($this->recall('filter',false)){
			if($this->recall('segment',false))
				$model_jobandvacancy->addCondition('segment_id',$this->recall('segment',false));
				
			if($this->recall('state',false))
				$model_jobandvacancy->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false))
				$model_jobandvacancy->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false))
				$model_jobandvacancy->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('min_package',false))
				$model_jobandvacancy->addCondition('min_package','>=',$this->recall('min_package',false));

			if($search=$this->recall('search',false)){
				$model_jobandvacancy->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN NATURAL LANGUAGE MODE)');
				$model_jobandvacancy->setOrder('Relevance','Desc');
				$model_jobandvacancy->addCondition('Relevance','>','0');
			}
		}else{
				$model_jobandvacancy->addCondition('state_id',-1);

		}


		

		$v->setModel($model_jobandvacancy);
	

		if($form->isSubmitted()){

			$this->forget('filter');
			$this->forget('segment');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('min_package');
			$this->forget('search');

			$v->js()->reload(array('segment'=>$form->get('segment'),
									'state'=>$form->get('state_id'),
									'city'=>$form->get('city_id'),
									'tehsil'=>$form->get('tehsil_id'),
									'min_package'=>$form->get('min_package'),
									'search'=>$form->get('search'),
									'filter'=>'1'))->execute();
		}
	}
}