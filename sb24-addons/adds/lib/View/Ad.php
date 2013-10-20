<?php

namespace adds;

class View_Ad extends \CompleteLister{
	public $i=1;

	function formatRow(){
		$this->current_row['height'] = $this->model->ref('adblock_id')->get('height');
		if($this->i != 1) $this->current_row['active']="";
		$this->i++;
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
		return array('view/ad');
	}
}