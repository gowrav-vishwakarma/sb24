<?php

namespace adds;

class View_AdBlock extends \View{

	function setModel($block_model){
		$ads= $block_model->ref('adds/Ad');
		$ads=$this->add('adds/View_Ad')->setModel($ads);
		parent::setModel($block_model);
	}

	// function render(){
	// 	// $this->js(true)->carousel(array('interval'=> $this->model['rotation_time']*1000));
	// 	parent::render();
	// }
}