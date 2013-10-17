<?php
class page_directory_manager extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tabs->addtabURL('directory_new','Add New Directory');
		// $tabs->addtabURL('directory_religion','Religions');

	}
}