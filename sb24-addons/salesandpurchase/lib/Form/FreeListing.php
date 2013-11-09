<?php

namespace businessdirectory;

class Form_FreeListing extends \Form{
	function init(){
		parent::init();
		$this->addClass('stacked');
	}

	// function defaultTemplate(){
	// 	return array('form_hybrid');
	// }
}