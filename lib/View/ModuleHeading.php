<?php

class View_ModuleHeading extends View{
	public $heading;
	private $is_set=false;
	private $login_btn_spot;
	private $login_btn;
	function init(){
		parent::init();
		$cols = $this->add('Columns');
		$left = $cols->addColumn(6);
		$right = $cols->addColumn(6);
		$left->add('View')
				->setElement('img')
				->setAttr('src','sabkuch.png')
				->addClass('top_left_image')
				;
				
		$btn=$this->login_btn_spot=$right->add('View');
		$this->login_btn_spot->setAttr('align','right');
		$this->login_btn=$this->login_btn_spot->add('View');
		$this->login_btn->add('View')->setElement('a')->setAttr('href','#')->add('View')->set('Register / login')
				->addStyle('margin-left','280px')
				->addStyle('padding','18px')
				->addClass('shine1');
							
		$this->heading = $this->login_btn_spot->add('H3')->setStyle(array('font-family'=> 'Snippet','sans-serif',' font-weight'=>' bold','color'=>'saddlebrown'));


		$btn->js('click',$this->js()->univ()->redirect('memberpanel_page_dashboard'));
		return $this;
	}

	function set($text){
		$this->heading->set($text);
		$this->is_set=true;
		return $this;
	}

	function recursiveRender(){
		if(!$this->is_set){
			$this->heading->destroy();
		}else{
			$this->login_btn->destroy();	
		}
		parent::recursiveRender();
	}

	function sub($text){
		$this->heading->sub($text);
	}
}