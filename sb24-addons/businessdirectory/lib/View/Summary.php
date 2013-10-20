<?php

namespace businessdirectory;

class View_Summary extends \View{

	function init(){
		parent::init();

		$crud=$this->add("CRUD");
		$crud->setModel($this->api->auth->model->ref('businessdirectory/FreeListing'));

	}

	// function defaultTemplate(){
		// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css'
		// 		)
		// 	)->setParent($l);

	// 	$this->api->jquery->addStylesheet('abcd','.css');
	// 	return array('view/summary');
	// }
}