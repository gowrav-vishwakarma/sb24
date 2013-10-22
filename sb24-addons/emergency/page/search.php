<?php

class page_emergency_page_search extends page_base_site {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->setModel('emergency/Listing',array('state_id','city_id','tehsil_id','category_id'));
		$form->addSubmit('Filter');
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id')));
		}

		$grid = $this->add('Grid');
		$grid->setModel('emergency/Listing');

	}
}