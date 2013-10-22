<?php

namespace businessdirectory;

class View_Listing extends \CompleteLister{

	function formatRow(){
		$js = $this->api->js('click')->univ()->frameURL("Details for " . $this->model['name'],$this->api->url('businessdirectory_page_more',array('listing_id'=>$this->model->id)));
		$this->current_row['more']=$js;
		parent::formatRow();
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

		$this->api->jquery->addStylesheet('abcd','.css');
		return array('view/summary');
	}
}