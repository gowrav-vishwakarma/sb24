<?php

class page_tracker_page_admin_index extends Page {
	function page_index(){

		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std'),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode'),'Pin Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc'),'MIRC /IFSC');
		$mobile_tab = $tabs->addTab('Mobile');
		$vehicle_tab = $tabs->addTab('Vehicle');


	}

	function page_std(){
		$std_crud = $this->add('CRUD');
		$std_crud->setModel('tracker/STDListing');
		$std_crud->add('Controller_PlaceSelector');
	}
	
	function page_pincode(){
		$pincode_crud = $this->add('CRUD');
		$pincode_crud->setModel('tracker/PINCODEListing');
		$pincode_crud->add('Controller_PlaceSelector');
	}

	function page_mirc(){
		$mirc_crud = $this->add('CRUD');
		$mirc_crud->setModel('tracker/MIRCListing');
		$mirc_crud->add('Controller_PlaceSelector');
	}


}