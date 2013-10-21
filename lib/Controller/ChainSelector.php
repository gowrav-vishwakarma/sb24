<?php

class Controller_ChainSelector extends AbstractController{
	public $chain_fields=array();
	public $force_selection=true;

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

		for($i=0; $i < count($this->chain_fields); $i++){
			if($field_one = $object->hasElement($this->chain_fields[$i]) and $field_two=$object->hasElement($this->chain_fields[$i+1])){
			
			if($_GET['changed_field'] == $this->chain_fields[$i]){
				$field_two->model->addCondition($this->chain_fields[$i],$_GET['field_value']);
			}else{
				if(($object->model AND !$object->model->loaded()) or $this->force_selection)
					$field_two->model->addCondition($this->chain_fields[$i],-1);
			}
			$field_one->js('change',$object->js()->atk4_form('reloadField',$this->chain_fields[$i+1],array($this->api->url(),'field_value'=>$field_one->js()->val(), 'changed_field'=>$this->chain_fields[$i])));
			}	
		}
		
	}
}