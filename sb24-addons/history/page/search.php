<?php

class page_history_page_search extends page_base_site {
	function init(){
		parent::init();
		$this->api->stickyGET($_GET['filter']);
		$this->api->stickyGET($_GET['state']);
		$this->api->stickyGET($_GET['city']);
		$this->api->stickyGET($_GET['area']);
		$this->api->stickyGET($_GET['placetype']);
		$this->api->stickyGET($_GET['search']);

		$this->add('View_ModuleHeading')->set('Find Places')->sub('Search via State, City and Place Type');
		$fields = array(
				'state_id'=>array('type'=>'dropdown', 'model'=>'State', 'emptyText' =>'Please Select State'),
				'city_id'=>array('type'=>'dropdown', 'model'=> 'City', 'emptyText' =>'Please Select City'),
				'area_id'=>array('type'=>'dropdown', 'model' =>'Area', 'emptyText' =>"Please Select Area"),
				'placetype_id'=>array('type'=>'dropdown','model'=>'history/PlaceType', 'emptyText'=>'Please select Place Type'),
				'search'=>array('type'=>'line')
			);

		$chain_fields=array(
				'city_id'=>'state_id',
				'area_id'=>'city_id'
			);

		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		$list = $this->add('history/View_Lister');
		$result = $this->add('history/Model_Place');
		if($_GET['filter']){
			if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
			if($_GET['city']) $result->addCondition('city_id',$_GET['city']);
			if($_GET['area']) $result->addCondition('area_id',$_GET['area']);
			if($_GET['placetype']) $result->addCondition('placetype_id',$_GET['placetype']);
			
			if($_GET['search']){
				$result->addExpression('relevance')->set('MATCH(short_description, about) AGAINST("'.str_replace('"', '\"', $_GET['search']).'" IN BOOLEAN MODE)');
				$result->addCondition('relevance','<>',0);
				$result->setOrder('relevance','desc');
			}
		}

		// if(!$_GET['filter']) {
		// 	$result->addCondition('id',-1);
		// }

		$list->addPaginator(1);
		$list->setModel($result);

		if($form->isSubmitted()){
			$list->js()->reload(array(
					'state'=>$form['state_id'],
					'city'=>$form['city_id'],
					'area'=>$form['area_id'],
					'placetype'=>$form['placetype_id'],
					'search'=>$form['search'],
					'filter'=>'1'
				))->execute();
		}



	}
}