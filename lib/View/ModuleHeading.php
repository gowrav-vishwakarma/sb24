<?php

class View_ModuleHeading extends View{
	public $heading;
	function init(){
		parent::init();
		$cols = $this->add('Columns');
		$left = $cols->addColumn(7);
		$right = $cols->addColumn(5);
		$this->heading=$left->add('H3');
		$right->add('View')
				->setElement('img')
				->setAttr('src','logo.png')
				->setStyle('max-width','100%')
							;
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