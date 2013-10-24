<?php

class page_distance_page_search extends page_base_site {
	function init(){
		parent::init();

		$model=$this->add('distance/Model_City');
		$model_distance_listing=$this->add('distance/Model_Listing');
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$city_from_field=$form->addField('dropdown','from_city');
		$city_from_field->setModel($model);

		$city_to_field=$form->addField('dropdown','to_city');
		$city_to_field->setModel($model);
		$form->addSubmit('Search');

		$v=$this->add('distance/View_Listing');
		$heading = $v->add('H3')->set('Distance');

		if($_GET['filter']){
			$city1=$_GET['from_city'];
			$city2=$_GET['to_city'];
				$model_distance_listing->_dsql()
					->where("(city_1_id=$city1 and city_2_id=$city2) or (city_1_id=$city2 and city_2_id=$city1)");
				$model2=$this->add('distance/Model_Listing');
				$model2->_dsql()
					->where("(city_1_id=$city1 and city_2_id=$city2) or (city_1_id=$city2 and city_2_id=$city1)");
				$model2->tryLoadAny();
			$heading->sub('From ' . $model2['city_1']. " to " . $model2['city_2']);
		}else{
			$model_distance_listing->addCondition('city_1_id',-1);
		}		
		
		$v->setModel($model_distance_listing);

		if($form->isSubmitted()){
			if($form->get('from_city')==$form->get('to_city'))
				$form->displayError('from_city','You can not select same city');
			$v->js()->reload(array('from_city'=>$form->get('from_city'),
									'to_city'=>$form->get('to_city'),
									'filter'=>'1'))->execute();
		}
	}
}