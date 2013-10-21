<?php

class Controller_PlaceSelector extends AbstractController{
	function init(){
		parent::init();

		if($this->owner instanceof CRUD)
			if($this->owner->form)
				$object=$this->owner->form;
			else
				$object=null;
		else if($this->owner instanceof Form)
			$object=$this->owner;
		else
			throw $this->exception('PlaceSelector Can only be implemented on CRUD And Forms')
						->addMoreInfo('Applied On',get_class($this->owner));

		if($object==null) return;

		if($state_field = $object->hasElement('state_id') and $city_field=$object->hasElement('city_id')){
			
			if($_GET['state_id']){
				$city_field->model->addCondition('state_id',$_GET['state_id']);
			}else{
				$city_field->model->addCondition('state_id',-1);
			}
			$state_field->js('change',$object->js()->atk4_form('reloadField','city_id',array($this->api->url(),'state_id'=>$state_field->js()->val())));
			// throw new Exception("Error Processing Request ".$object, 1);
		}
	}
}