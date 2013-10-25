<?php


class page_event_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Find Events')->sub('Search via State, City, Type of event and date');
		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State', 'mandatory'=>true),
			'city_id'=>array('type'=>'dropdown','model'=>'City','emptyText'=>'Please Select City', 'mandatory'=>true),
			'event_type_id'=>array('type'=>'dropdown','model'=>'event/Type','emptyText'=>'Please Select Event Type'),
			'event_from_date'=>array('type'=>'DatePicker'),
			'event_to_date'=>array('type'=>'DatePicker'),
			);

		$chain_fields=array("city_id"=>'state_id');
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));

		$list = $this->add('event/View_Listing');

		if($form->isSubmitted()){
			$list->js()->reload(array(
					'state'=>$form['state_id'],
					'city'=>$form['city_id'],
					'event_type'=>$form['event_type_id'],
					'from_date'=>$form['event_from_date'],
					'to_date'=>$form['event_to_date'],
				))->execute();
		}

		$result = $this->add('event/Model_Listing');

		$list->setModel($result);

	}
}