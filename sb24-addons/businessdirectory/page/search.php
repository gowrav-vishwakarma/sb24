<?php

class page_businessdirectory_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Find Comapnies/Buisness Listing')->sub('Search via State, City, Industry or Major Field');
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State'),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Please Select City'),
			'category_id'=>array('type'=>'dropdown','model'=>'Category','emptyText'=>'Please Select Industry'),
			'subcategory_id'=>array('type'=>'dropdown','model'=>'SubCategory','emptyText'=>'Please Select Major Field'),
			'search'=>array('type'=>'line'),
			);

		$chain_fields=array("city_id"=>'state_id','subcategory_id'=>'category_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		$business_listing = $this->add('businessdirectory/View_Listing');
		$result = $this->add('businessdirectory/Model_Listing');

		if($_GET['filter']){
			if($_GET['state_id'])
				$result->addCondition('state_id',$_GET['state_id']);
			if($_GET['city_id'])
				$result->addCondition('city_id',$_GET['city_id']);
			if($_GET['category_id'])
				$result->addCondition('category_id',$_GET['category_id']);
			if($_GET['subcategory_id'])
				$result->addCondition('subcategory_id',$_GET['subcategory_id']);

			if($_GET['string']){
				$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$_GET['string'].' IN BOOLEAN MODE")');
				$result->setOrder('Relevance','Desc');
				// $result->addCondition('Relevance','>','0.5');
			}
		}

		$business_listing->setModel($result);
		if($form->isSubmitted()){
			$business_listing->js()->reload(array(
												'string'=>$form->get('search'),
												'state_id'=>$form->get('state_id'),
												'city_id'=>$form->get('city_id'),
												'category_id'=>$form->get('category_id'),
												'subcategory_id'=>$form->get('subcategory_id'),
												'filter'=>'1'))->execute();
		}

	}
}