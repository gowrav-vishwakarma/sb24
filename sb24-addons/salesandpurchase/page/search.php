<?php

class page_salesandpurchase_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('View_ModuleHeading')->set('Find Sales And Purchase  Listing')->sub('Search via State, City, Category or Subcategory Field');
		
		
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State'),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Please Select City'),
			'category_id'=>array('type'=>'dropdown','model'=>'salesandpurchase/Category','emptyText'=>'Please Select Category'),
			'subcategory_id'=>array('type'=>'dropdown','model'=>'salesandpurchase/SubCategory','emptyText'=>'Please Select SubCategory Field'),
			'min_amount'=>array('type'=>'line'),
			'search'=>array('type'=>'line'),
			);

		$chain_fields=array("city_id"=>'state_id','subcategory_id'=>'category_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		$salesandpurchase_listing = $this->add('salesandpurchase/View_Listing');
		$result = $this->add('salesandpurchase/Model_Listing');

		if($_GET['filter']){
			if($_GET['state_id'])

				$result->addCondition('state_id',$_GET['state_id']);
			if($_GET['city_id'])
				$result->addCondition('city_id',$_GET['city_id']);
			if($_GET['category_id'])
				$result->addCondition('category_id',$_GET['category_id']);
			if($_GET['subcategory_id'])
				$result->addCondition('subcategory_id',$_GET['subcategory_id']);
			if($_GET['min_amount'])
				$result->addCondition('price','>=',$_GET['min_amount']);

			if($_GET['string']){
				$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$_GET['string'].'" IN BOOLEAN MODE)');
				$result->setOrder('Relevance','Desc');
			}

			
		}


		$salesandpurchase_listing->addPaginator(2);
		$salesandpurchase_listing->setModel($result);
		if($form->isSubmitted()){
			$salesandpurchase_listing->js()->reload(array(
												'min_amount'=>$form->get('min_amount'),
												'string'=>$form->get('search'),
												'state_id'=>$form->get('state_id'),
												'city_id'=>$form->get('city_id'),
												'category_id'=>$form->get('category_id'),
												'subcategory_id'=>$form->get('subcategory_id'),
												'filter'=>'1'))->execute();
		}





	}
}