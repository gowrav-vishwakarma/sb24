<?php

class page_distance_page_admin_index extends page_base_admin {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$distance_tab=$tabs->addTab('Manage Distance Directory');
		$distance_crud=$distance_tab->add('CRUD');
		$distance_crud->setModel('distance/Listing');

		if($g=$distance_crud->grid){
			$g->addQuickSearch(array('city_1','city_2'));
			$g->addPaginator(30);
		}

	}
}