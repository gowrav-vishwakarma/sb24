<?php

class page_blooddoner_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$model=$this->add('blooddoner/Model_Listing');
		$crud->add('misc/Export');
		$crud->setModel($model);

		 if($crud->form){
			$crud->form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id','area_id'=>'tehsil_id'),'force_selection'=>true));
		}

	}
}

