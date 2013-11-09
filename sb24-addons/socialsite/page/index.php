<?php
class page_socialsite_page_index extends page_base_site {


	function defaultTemplate(){
		$l=$this->api->locate('addons','socialsite', 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons','socialsite'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/social');
	}
}