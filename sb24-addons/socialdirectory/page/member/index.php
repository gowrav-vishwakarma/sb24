<?php

class page_socialdirectory_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->addClass('stacked');
		$form->setModel($this->api->auth->model->reload(),'social');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Update My Info')->addStyle(array('margin-top'=>'5%','margin-left'=>'30%'))->addClass('shine1')->js('click')->submit();

		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id','cast_id'=>'religion_id','subcast_id'=>'cast_id')));
		}

		if($form->isSubmitted()){
			$form->update();
			$form->js(null, $form->js()->reload())->univ()->successMessage("Information Updated")->execute();
		}

	}
}