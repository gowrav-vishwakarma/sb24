<?php

namespace socialdirectory;

class View_Lister extends \CompleteLister{

	public $paginator;


	function formatRow(){
		$js = $this->api->js('click')->univ()->frameURL("Details for " . $this->model['name'],$this->api->url('socialdirectory_page_more',array('member_id'=>$this->model->id)),array('width'=>'65%'))->setClass('atk-icon atk-icons-red atk-icon-basic-ex');
		$this->current_row['more']=$js;
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

		return array('view/list');

	}
}