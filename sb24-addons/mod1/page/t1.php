<?php

// namespace mod1;

class page_mod1_page_t1 extends \Page {
	function init(){
		parent::init();

		$this->add('View_Info')->set("oke");

	}
}