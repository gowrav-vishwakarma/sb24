<?php

class page_history_page_search extends page_base_site {
	function init(){
		parent::init();
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('placetype');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		$this->memorize("placetype",$_GET['placetype']?:$this->recall('placetype',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('View_ModuleHeading');//->set('Find Places')->sub('Search via State, City and Place Type');
		$fields = array(
				'state_id'=>array('type'=>'dropdown', 'model'=>'State', 'emptyText' =>'Please Select State'),
				'city_id'=>array('type'=>'dropdown', 'model'=> 'City', 'emptyText' =>'Please Select City'),
				'tehsil_id'=>array('type'=>'dropdown', 'model' =>'Tehsil', 'emptyText' =>"Please Select Area"),
				'area_id'=>array('type'=>'dropdown', 'model' =>'Area', 'emptyText' =>"Please Select Area"),
				'placetype_id'=>array('type'=>'dropdown','model'=>'history/PlaceType', 'emptyText'=>'Please select Place Type'),
				'search'=>array('type'=>'line')
			);

		$chain_fields=array(
				'city_id'=>'state_id',
				'tehsil_id'=>'city_id',
				'area_id'=>'tehsil_id'
			);

		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields,null));
		
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span4'),'after','city_id')
                ->move($form->addSeparator('noborder span3'),'after','area_id')
                ->now();

		$list = $this->add('history/View_Lister');
		$result = $this->add('history/Model_Place');
		if($xfilter = $this->recall('filter',false)){
			if($state = $this->recall('state',false)) $result->addCondition('state_id',$state);
			if($city = $this->recall('city',false)) $result->addCondition('city',$city);
			if($tehsil = $this->recall('tehsil',false)) $result->addCondition('tehsil_id',$tehsil);
			if($area = $this->recall('area',false)) $result->addCondition('area_id',$_GET['area']);
			if($area = $this->recall('placetype',false)) $result->addCondition('placetype_id',$_GET['placetype']);
			
			if($search = $this->recall('search',false)){
				$result->addExpression('relevance')->set('MATCH(short_description, about) AGAINST("'.str_replace('"', '\"', $search).'" IN BOOLEAN MODE)');
				$result->addCondition('relevance','<>',0);
				$result->setOrder('relevance','desc');
			}
		}

		if(!$filter = $this->recall('filter',false)) {
			$result->addCondition('id',-1);
		}

		$list->setModel($result);
		$list->addPaginator(1);

		if($form->isSubmitted()){
			$list->js()->reload(array(
					'state'=>$form['state_id'],
					'city'=>$form['city_id'],
					'tehsil'=>$form['tehsil_id'],
					'area'=>$form['area_id'],
					'placetype'=>$form['placetype_id'],
					'search'=>$form['search'],
					'filter'=>'1'
				))->execute();
		}



	}
}