<?php

namespace emergency;

class View_Area extends \View{

	function formatRow(){
		parent::formatRow();


	}

	function setModel($model){
		$this->template->trySet('random',rand(10000,99999));
		return parent::setModel($model);
	}
	
	function defaultTemplate(){
		$l=$this->api->locate('addons','emergency','location');
		$this->api->pathfinder->addLocation($this->api->locate('addons','emergency'),array(
		    'template'=>'templates'
		))->setParent($l);

		return array('view/area');
	}
}