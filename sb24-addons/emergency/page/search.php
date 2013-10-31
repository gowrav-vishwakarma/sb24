<?php

class page_emergency_page_search extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('category');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("category",$_GET['category']?:$this->recall('category',false));

		$this->add('View_ModuleHeading');//->set('Get Emergency/Important Numbers')->sub('Search via State, City, Tehsil or Category');

		$form=$this->add('Form');
		$grid = $this->add('Grid');
		
		$form->setModel('emergency/Listing',array('state_id','city_id','tehsil_id','category_id'));
		// $form->addSubmit('Filter');

		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span4'),'after','city_id')
                ->move($form->addSeparator('noborder span3'),'after','category_id')
                ->now();
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span12')->js('click')->submit();
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id')));
		}

		if($form->isSubmitted()){
			$grid->js()->reload(array(
					"state" => $form['state_id'],
					"city" => $form['city_id'],
					"tehsil" => $form['tehsil_id'],
					"category" => $form['category_id'],
					"filter" => '1'
				))->execute();
		}

		$result = $this->add('emergency/Model_Listing');

		$grid->add('H4',null,'top_1')->set('Search Result');
		if($this->recall('filter',false)){
			if($this->recall('state',false)) $result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false)) $result->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false)) $result->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('category',false)) $result->addCondition('category_id',$this->recall('category',false));
		}
		else{
			$result->addCondition('state_id',-1);
		}

		$grid->addPaginator(1);
		$grid->setModel($result);

	}
}