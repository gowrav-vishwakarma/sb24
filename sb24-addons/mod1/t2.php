<?php

class page_mod1_t2 extends Page {
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$crud->setModel('mod1\Core');

	}
}