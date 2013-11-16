<?php

class Form_Field_RichText extends Form_Field_Text{
	function init(){
		parent::init();
		// $this->api->jquery->addStaticInclude('tinymce/jquery.tinymce.min');
		// $this->js()->_load('tinymce/jquery.tinymce.min');
	}

	function getInput($attr=array()){
	    return parent::getInput(array_merge(
	        array(
	            'value'=>'',
	             ),$attr
	        ));
	}

	function render(){

		$this->js(true)->tinymce(array('script_url' => 'templates/js/tinymce/tinymce.min.js'));

		// $this->js(true)->univ()->createRTE(array(
		// 									'toolbar'=>'basic',
		// 									'width' => '600',
		// 									'height'=>'300'
		// 									)
		// 						);
		parent::render();
	}

}