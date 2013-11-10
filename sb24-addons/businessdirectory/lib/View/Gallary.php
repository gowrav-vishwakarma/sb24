<?php

namespace businessdirectory;

class View_Gallary extends \CompleteLister{
	public $paginator;
	
	// function addPaginator($ipp = 25, $options = null)
 //    {
 //        // adding ajax paginator
 //        if ($this->paginator) {
 //            return $this->paginator;
 //        }
 //        $this->paginator = $this->add('Paginator', $options);
 //        $this->paginator->ipp($ipp);
 //        return $this;
 //    }

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
		return array('view/gallary');
	}
}