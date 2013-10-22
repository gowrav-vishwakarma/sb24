<?php

class page_emergency_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Get Emergency/Important Numbers')->sub('Search via State, City, Tehsil or Category');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$grid = $this->add('Grid');
		
		$form->setModel('emergency/Listing',array('state_id','city_id','tehsil_id','category_id'));
		$form->addSubmit('Filter');
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id')));
		}

		if($form->isSubmitted()){
			$grid->js()->reload(array(
					"state" => $form['state_id'],
					"city" => $form['city_id'],
					"tehsil" => $form['tehsil_id'],
					"category" => $form['category_id']
				))->execute();
		}

		$result = $this->add('emergency/Model_Listing');

		$grid->add('H4',null,'top_1')->set('Search Result');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['city']) $result->addCondition('city_id',$_GET['city']);
		if($_GET['tehsil']) $result->addCondition('tehsil_id',$_GET['tehsil']);
		if($_GET['category']) $result->addCondition('category_id',$_GET['category']);

		$grid->setModel($result);

	}
}