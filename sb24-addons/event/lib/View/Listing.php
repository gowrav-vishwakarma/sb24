<?php

namespace event;

class View_Listing extends \CompleteLister{

	function formatRow(){
		$js = $this->api->js('click')->univ()
			->frameURL("Details for " . $this->model['name'],$this->api->url('event_page_more',array('event_id'=>$this->model->id)));
		$this->current_row['more']=$js;

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