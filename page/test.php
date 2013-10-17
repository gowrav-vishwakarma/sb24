<?php
class page_test extends Page {
	function init(){
		parent::init();

		$this->add('Lister')->setModel('Directory_Core');

	}
}