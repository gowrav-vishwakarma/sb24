<?php

class View_ModuleHeading extends View{
	public $heading;
	private $is_set=false;
	private $login_btn_spot;
	private $login_btn;
	function init(){
		parent::init();
		$cols = $this->add('Columns');
		$left = $cols->addColumn(2);
		$center = $cols->addColumn(6);
		$right = $cols->addColumn(4);
		// $center->add('View')
		// 		->setElement('img')
		// 		// ->setAttr('src','sabkuch.png')
		// 		->setAttr('width','100%')
		// 		->setStyle('margin-top','-10px')
		// 		->addClass('top_left_image')
		// 		;
		// $left->add('Button',null,null,array('view/mybutton','button'))->set('Go Back')->addStyle(array('margin-top'=>'0px'))->addClass(' shine1')->js('click','window.history.back();');
		// $btn=$this->login_btn_spot=$right->add('View');
		// $this->login_btn_spot->setAttr('align','right');
		// $this->login_btn=$this->login_btn_spot->add('View');
		// $this->login_btn->add('View')->setElement('a')->setAttr('href','#')->add('View')->set('Register / login')
		// 		->addStyle('margin-left','280px')
		// 		->addStyle('padding','18px')
		// 		->addClass('shine1');
							
		$center->add('HtmlElement')->setElement('img')->setAttr('src',$this->api->sb24_config['front_image'])->setAttr('align','center')->setStyle(array('margin-bottom'=>'-28px','margin-left'=>'237px'));
		$this->setStyle('margin-bottom','15px');

		// $btn->js('click',$this->js()->univ()->redirect('memberpanel_page_dashboard'));
		return $this;
	}

	function set($text){
		$this->heading->setHTML($text);
		$this->is_set=true;
		return $this;
	}

	function recursiveRender(){
		if(!$this->is_set){
			// $this->heading->destroy();
		}else{
			// $this->login_btn->destroy();	
		}
		parent::recursiveRender();
	}

	function sub($text){
		// $this->heading->sub($text);
	}
}