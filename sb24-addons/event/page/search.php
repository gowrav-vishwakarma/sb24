<?php


class page_event_page_search extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('event_type');
			$this->forget('to_date');
			$this->forget('from_date');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("event_type",$_GET['event_type_id']?:$this->recall('event_type',false));
		$this->memorize("to_date",$_GET['to_date']?:$this->recall('to_date',false));
		$this->memorize("from_date",$_GET['from_date']?:$this->recall('from_date',false));
		$this->add('View_ModuleHeading')->set('Find Events')->sub('Search via State, City, Type of event and date');
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State'),
			'event_from_date'=>array('type'=>'DatePicker'),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Please Select City'),
			'tehsil_id'=>array('type'=>'dropdown','model'=>'Tehsil','emptyText'=>'Please Select Tehsil'),
			'event_type_id'=>array('type'=>'dropdown','model'=>'event/Type','emptyText'=>'Please Select Event Type'),
			'event_to_date'=>array('type'=>'DatePicker'),
			);

		$chain_fields=array("city_id"=>'state_id','tehsil_id'=>'city_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		 $form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span4'),'after','tehsil_id')
                ->move($form->addSeparator('noborder span3'),'after','event_from_date')
                ->now();
		$list = $this->add('event/View_Listing');
		if($form->isSubmitted()){
			$list->js()->reload(array(
					'state'=>$form['state_id'],
					'city'=>$form['city_id'],
					'tehsil'=>$form['tehsil'],
					'event_type'=>$form['event_type_id'],
					'from_date'=>$form['event_from_date']?:'0',
					'to_date'=>$form['event_to_date']?:"0",
					'filter'=>'1'
				))->execute();
		}
		$result = $this->add('event/Model_Listing');
		if($this->recall('filter',false)){
			if($this->recall('state',false)) $result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false))  $result->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false))  $result->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('event_type',false)) $result->addCondition('event_type_id',$this->recall('event_type',false));
			if($this->recall('to_date',false)) $result->addCondition('event_date','<=',$this->recall('to_date',false));
			if($this->recall('from_date',false)) $result->addCondition('event_date','>=',$this->recall('from_date',false));
		}else{
			$result->addCondition('state_id',-1);
		}


		$list->setModel($result);
		$list->addPaginator(10);

	}
}