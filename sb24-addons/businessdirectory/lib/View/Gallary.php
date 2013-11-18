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

	function formatRow(){
		$js = $this->js()->univ()->frameURL($this->model['name'], $this->api->url('businessdirectory_page_gallarypic',array('image_id'=>$this->model->id)));
		$this->current_row['click_action'] = "javascript:".$js;
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
		return array('view/gallary');
	}
}