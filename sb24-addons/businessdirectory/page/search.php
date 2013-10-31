<?php

class page_businessdirectory_page_search extends page_base_site {
	function init(){
		parent::init();
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('industry');
			$this->forget('segment');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		$this->memorize("industry",$_GET['industry']?:$this->recall('industry',false));
		$this->memorize("segment",$_GET['segment']?:$this->recall('segment',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('View_ModuleHeading');//->set('Find Comapnies/Buisness Listing')->sub('Search via State, City, Industry or Major Field');
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State'),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Please Select City'),
			'search'=>array('type'=>'line'),
			'tehsil_id'=>array('type'=>'dropdown','model'=>'Tehsil','emptyText'=>'Please Select City'),
			'area_id'=>array('type'=>'dropdown','model'=>'Area','emptyText'=>'Please Select City'),
			'industry_id'=>array('type'=>'dropdown','model'=>'businessdirectory/Industry','emptyText'=>'Please Select Industry'),
			'segment_id'=>array('type'=>'dropdown','model'=>'businessdirectory/Segment','emptyText'=>'Please Select Major Field'),
			);

		$chain_fields=array("city_id"=>'state_id',"tehsil_id"=>"city_id","area_id"=>'tehsil_id','segment_id'=>'industry_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));

		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder span4'),'first')
                ->move($form->addSeparator('noborder span4'),'after','search')
                ->move($form->addSeparator('noborder span3'),'after','area_id')
                ->now();

		$business_listing = $this->add('businessdirectory/View_Listing');
		$result = $this->add('businessdirectory/Model_Listing');

		if($this->recall('filter',false)){
			// throw new Exception("Error Processing Request", 1);
			
			if($this->recall('state',false))
				$result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false))
				$result->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false))
				$result->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('area',false))
				$result->addCondition('area_id',$this->recall('area',false));
			if($this->recall('industry',false))
				$result->addCondition('industry_id',$this->recall('industry',false));
			if($this->recall('segment',false))
				$result->addCondition('segment_id',$this->recall('segment',false));

		}else{
				$result->addCondition('state_id',-1);
		}
				
		$result->addCondition('is_active',true);
		$result->setOrder('is_paid','asc');
		$result->setOrder('created_on');
		$result->setOrder('payment_received','desc');
		if($search=$this->recall('search',false)){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN NATURAL LANGUAGE MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','>','0');
		}

		$business_listing->setModel($result);
		$business_listing->addPaginator(10);
		if($form->isSubmitted()){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('industry');
			$this->forget('segment');
			$this->forget('search');
			$business_listing->js()->reload(array(
												'search'=>$form->get('search'),
												'state'=>$form->get('state_id'),
												'city'=>$form->get('city_id'),
												'tehsil'=>$form->get('tehsil_id'),
												'area'=>$form->get('area_id'),
												'industry'=>$form->get('industry_id'),
												'segment'=>$form->get('segment_id'),
												'filter'=>'1'))->execute();
		}

	}
}