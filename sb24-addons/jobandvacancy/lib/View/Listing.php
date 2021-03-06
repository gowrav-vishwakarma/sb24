<?php

namespace jobandvacancy;

class View_Listing extends \CompleteLister{
	public $paginator;
	function formatRow(){
		$js = $this->api->js('click')->univ()->frameURL("Details for " . $this->model['name'],$this->api->url('jobandvacancy_page_more',array('listing_id'=>$this->model->id)),array('width'=>'65%'));
		$this->current_row['more']=$js;
		if($this->current_row['company_logo']=='')  $this->current_row['company_logo'] = 'sabkuch.png';
		parent::formatRow();
	}

	function setModel($model){
		if($model->count()->getOne() > 0) $this->template->tryDel('not_found');
		parent::setModel($model);
	}


	
	function addPaginator($ipp = 25, $options = null)
    {
        // adding ajax paginator
        if ($this->paginator) {
            return $this->paginator;
        }
        $this->paginator = $this->add('Paginator', $options);
        $this->paginator->ipp($ipp);
        return $this;
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