<?php

class View_ModuleHeading extends View{
	public $heading;
	function init(){
		parent::init();
		$cols = $this->add('Columns');
		$left = $cols->addColumn(8);
		$right = $cols->addColumn(4);
		$this->heading = $left->add('H2');
		$btn=$right->add('View')
				->setElement('img')
				->setAttr('src','login.png')
				->setAttr(array('width'=>'250px','height'=>'100px'))
							;


		$btn->js('click',$this->js()->univ()->redirect('memberpanel_page_dashboard'));
		return $this;
	}

	function set($text){
		$this->heading->set($text);
		return $this;
	}

	function sub($text){
		$this->heading->sub($text);
	}
}