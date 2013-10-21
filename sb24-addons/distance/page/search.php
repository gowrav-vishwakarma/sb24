<?php

class page_distance_page_search extends page_base_site {
	function init(){
		parent::init();

		$model=$this->add('Model_City');
		$model_distance_listing=$this->add('distance/Model_Listing');
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$city_from_field=$form->addField('dropdown','from_city');
		$city_from_field->setModel($model);

		$city_to_field=$form->addField('dropdown','to_city');
		$city_to_field->setModel($model);
		$form->addSubmit('Search');

		$v=$this->add('distance/View_Listing');

		if($_GET['filter']){

			$model_distance_listing->_dsql()
			->where(array(
					array('city_1_id',$_GET['from_city']),
					array('city_2_id',$_GET['to_city']),
				))
			->where(array(
					array('city_2_id',$_GET['from_city']),
					array('city_1_id',$_GET['to_city']),
				))
			;
			// $model_distance_listing->debug();
		}else{
			$model_distance_listing->addCondition('id',-1);
		}
		
		$v->setModel($model_distance_listing);

		if($form->isSubmitted()){
			$v->js()->reload(array('from_city'=>$form->get('from_city'),
									'to_city'=>$form->get('to_city'),
									'filter'=>'1'))->execute();
		}
	}
}