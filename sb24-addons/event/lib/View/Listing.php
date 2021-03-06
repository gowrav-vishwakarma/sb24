<?php

namespace event;

class View_Listing extends \CompleteLister{
		public $paginator;
	function formatRow(){
		$js = $this->api->js('click')->univ()
			->frameURL("Details for " . $this->model['name'],$this->api->url('event_page_more',array('event_id'=>$this->model->id)),array('width'=>'65%'));
		$this->current_row['more']=$js;

		if($this->current_row['event_picture'] == '') $this->current_row['event_picture'] = 'sabkuch.png';

		$this->api->auth->setModel('Member','username','password');
		if(!$this->api->auth->isLoggedIn()){
			
			$this->current_row['register'] = $this->api->js()->univ()->redirect($this->api->url('memberpanel_page_dashboard'));
			$this->current_row['register_label'] = "Login To Register";
		}else{
			if(strtotime($this->model['registration_last_date']) >= strtotime(date('Y-m-d'))){
				$js = $this->api->js('click')->univ()
						->frameURL("Register for " . $this->model['name'], $this->api->url('event_page_register',array('listing_id'=>$this->model->id)));
				$this->current_row['register'] = $js;			
			}else{
				$this->current_row['register'] = $this->api->js()->_enclose('dummy');
				$this->current_row['register_label'] = "Regisreation Closed";
			}
			
		}
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