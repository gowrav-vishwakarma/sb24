<?php
class page_admin_master extends page_base_admin{
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');

		$category_tab=$tabs->addTab('Business Categories');
		$category_crud=$category_tab->add('CRUD');
		$category_crud->setModel('Category');
		// $category_crud->addRef('businessdirectory/RegisteredCategory');

		$state_tab=$tabs->addTab('States');
		$state_crud=$state_tab->add('CRUD');
		$state_crud->setModel('State');

		$city_tabs=$tabs->addTab('City');
		$city_crud=$city_tabs->add('CRUD');
		$city_crud->setModel('City');


		$tehsil_tabs=$tabs->addTab('Tehsil');
		$tehsil_crud=$tehsil_tabs->add('CRUD');
		$tehsil_crud->setModel('Tehsil');

		$area_tabs=$tabs->addTab('Area');
		$area_crud=$area_tabs->add('CRUD');
		$area_crud->setModel('Area');

	}
}