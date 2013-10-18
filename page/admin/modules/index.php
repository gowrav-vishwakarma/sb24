<?php

class page_admin_modules_index extends page_base_admin {
	function init(){
		parent::init();

		$modules = array(
				'admin_modules_history_index' => 'History',
				'admin_modules_socialdirectory_index' => 'Social Directory'
			);

		$tabs=$this->add('Tabs');
		foreach($modules as $page=>$title){
			$tabs->addTabURL($page,$title);
		}

	}
}