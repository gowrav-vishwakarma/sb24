<?php

class page_history_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('View_ModuleHeading')->set('Find Places')->sub('Search via State, City and Place Type');
		$fields = array(
				'state_id'=>array('type'=>'dropdown', 'model'=>'State', 'emptyText' =>'Please Select State', 'mandatory'=>true),
				'city_id'=>array('type'=>'dropdown', 'model'=> 'City', 'emptyText' =>'Please Select City', 'mandatory'=>true),
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

		if($form->isSubmitted()){
			$list->js()->reload(array(
					'state'=>$form['state_id'],
					'city'=>$form['city_id'],
					'area'=>$form['area_id'],
					'placetype'=>$form['placetype_id'],
					'search'=>$form['search'],
				))->execute();
		}

		$result = $this->add('history/Model_Place');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);

		$list->setModel($result);

	}
}