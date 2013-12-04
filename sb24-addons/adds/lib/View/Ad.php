<?php

namespace adds;

class View_Ad extends \CompleteLister{
	public $i=1;

	function formatRow(){
		$this->current_row['height'] = $this->model->ref('adblock_id')->get('height');
		if($this->i != 1) $this->current_row['active']="";
		$this->i++;

		if($this->current_row['click_url'] == '') {
			$this->current_row['click_url']='#';
			$this->current_row['target']='';
			if($this->model['listing_id']){
				$js = $this->api->js()->univ()->frameURL("Details for " . $this->model['name'],$this->api->url('businessdirectory_page_more',array('listing_id'=>$this->model['listing_id'])),array('width'=>'65%'));
				$this->current_row['click_event']=$js;
			}else{
				$this->current_row['click_event']="javascript:;";
			}
		}

		if($this->current_row['email'] != ''){
			//Email User
		}
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