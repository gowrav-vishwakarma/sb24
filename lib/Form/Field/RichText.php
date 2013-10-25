<?php

class Form_Field_RichText extends Form_Field_Text{
	function init(){
		parent::init();
		$this->js()->_load('rte');
	}

	function getInput($attr=array()){
	    return parent::getInput(array_merge(
	        array(
	            'value'=>'',
	             ),$attr
	        ));
	}

	function render(){

		$this->js(true)->univ()->createRTE(array(
											'toolbar'=>'basic',
											'width' => '600',
											'height'=>'300'
											)
								);
		parent::render();
	}

}