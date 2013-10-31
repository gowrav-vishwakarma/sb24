<?php

class View_ModuleHeading extends View{
	public $heading;
	function init(){
		parent::init();
		$cols = $this->add('Columns');
		$left = $cols->addColumn(8);
		$right = $cols->addColumn(4);
		$btn=$left->add('View')
				->setElement('img')
				->setAttr('src','sabkuch.png');
				
		$btn=$right->add('View')->setElement('a')->setAttr('href','#')->add('View')
				->setElement('img')
				->setAttr('src','login.png')
				->setAttr(array('width'=>'250px','height'=>'100px'))
				->addClass('register_btn');
							;
		$this->heading = $this->add('H2');


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