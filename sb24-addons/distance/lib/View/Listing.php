<?php

namespace distance;

class View_Listing extends \View{
	public $paginator;
	function init(){
		parent::init();

	}

	function setModel($model){
		$model->tryLoadAny();
		if(!$model->loaded()) return;
		parent::setModel($model);
	}

	

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);

		// $this->api->jquery->addStylesheet('abcd','.css');
		return array('view/summary');
	}
}