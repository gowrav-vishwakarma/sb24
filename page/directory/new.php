<?php
class page_directory_new extends Page {
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$crud->setModel('Directory_Core');
	}
}