<?php

class Controller_FormWizard extends AbstractController{
	public $steps=array();
	public $order;
	public $moves;
	public $hideSave=true; 
	public $option;

	function init(){
		parent::init();
		$this->owner->js()->_load('formToWizard');
	}

	function addStep($title,$cut_field){
		$f=$this->owner;
		if($this->order){
			$s = $f->addSeparator();
			$t1=$f->add('View')->setElement('legend')->set($title);
			$this->moves= $this->order->move($s,'before',$cut_field)->move($t1,'after',$s);
		}else{ //First
			$this->order = $this->owner->add('Order');
			$t1=$f->add('View')->setElement('legend')->set($title);
			$this->moves= $this->order->move($t1,'first');
		}
	}

	function go(){
		$this->moves->now();
		if($this->hideSave) $this->option = array('submitButton'=> $this->owner->name."_form_submit");
		$this->owner->js(true)->formToWizard($this->option);
	}

}