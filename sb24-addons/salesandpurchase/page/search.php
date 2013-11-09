<?php

class page_salesandpurchase_page_search extends page_base_site {
	function init(){
		parent::init();
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('category');
			$this->forget('subcategory');
			$this->forget('min_amount');
			$this->forget('search');
			$this->forget('type');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		$this->memorize("category",$_GET['category']?:$this->recall('category',false));
		$this->memorize("subcategory",$_GET['subcategory']?:$this->recall('subcategory',false));
		$this->memorize("min_amount",$_GET['min_amount']?:$this->recall('min_amount',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));
		$this->memorize("type",$_GET['type']?:$this->recall('type',false));

		$this->add('View_ModuleHeading')->set('Find Sales And Purchase  Listing')->sub('Search via State, City, Category or Subcategory Field');
		
		
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Select State','span'=>2),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Select City','span'=>2),
			'tehsil_id'=>array('type'=>'dropdown','model'=>'Tehsil','emptyText'=>'Select Tehsil','span'=>2),
			'category_id'=>array('type'=>'dropdown','model'=>'salesandpurchase/Category','emptyText'=>'Select Category','span'=>2),
			'subcategory_id'=>array('type'=>'dropdown','model'=>'salesandpurchase/SubCategory','emptyText'=>'Select SubCategory','span'=>2),
			'min_amount'=>array('type'=>'line','span'=>2),
			'type'=>array('type'=>'dropdown','emptyText'=>'Select Type','span'=>2,'valueList'=>array('sales'=>'Sales','requirement'=>'Requirement')),
			'search'=>array('type'=>'line','span'=>9,),
			);

		$chain_fields=array("city_id"=>'state_id','tehsil_id'=>'city_id','subcategory_id'=>'category_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		$salesandpurchase_listing = $this->add('salesandpurchase/View_Listing');
		$result = $this->add('salesandpurchase/Model_Listing');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                ->move($form->addSeparator('noborder atk-row'),'after','type')
                // ->move($form->addSeparator('noborder atk-row'),'after','search')
                ->now();


		if($this->recall('filter',false)){
			if($this->recall('state',false))

				$result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false))
				$result->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false))
				$result->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('category',false))
				$result->addCondition('category_id',$this->recall('category',false));
			if($this->recall('subcategory',false))
				$result->addCondition('subcategory_id',$this->recall('subcategory',false));
			if($this->recall('min_amount',false))
				$result->addCondition('price','>=',$this->recall('min_amount',false));
			if($this->recall('type',false))
				$result->addCondition('posting_type',$this->recall('type',false));

			if($search=$this->recall('search',false)){
				$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
				$result->setOrder('Relevance','Desc');
				$result->addCondition('Relevance','<>',0);
			}

			
		}else{
			
		$result->addCondition('state_id',-1);
		}


		$salesandpurchase_listing->addPaginator(5);
		$salesandpurchase_listing->setModel($result);
		if($form->isSubmitted()){
			$salesandpurchase_listing->js()->reload(array(
												'min_amount'=>$form->get('min_amount'),
												'string'=>$form->get('search'),
												'state'=>$form->get('state_id'),
												'city'=>$form->get('city_id'),
												'category'=>$form->get('category_id'),
												'subcategory'=>$form->get('subcategory_id'),
												'type'=>$form->get('type'),
												'filter'=>'1'))->execute();
		}





	}
}