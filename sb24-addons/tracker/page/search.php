<?php

class page_tracker_page_search extends page_base_site {
	function page_index(){

		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std'),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode'),'Pin Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc'),'MIRC/IFSC');
		$mobile_tab = $tabs->addTab('Mobile');
		$vehicle_tab = $tabs->addTab('Vehicle');

	}

	function page_std(){
		$this->add('H3')->set('Find STD Code')->sub('Search via State, City, Area or STD Code');
		$std_grid = $this->add('Grid');
		$std_grid->setModel('tracker/STDListing');
		$std_grid->addQuickSearch(array('state','city','area','STD_code'));
		$std_grid->addPaginator(10);
	}

	function page_pincode(){
		$this->add('H3')->set('Find PIN Code')->sub('Search via State, City, Post Office or PIN Code');
		$pincode_grid = $this->add('Grid');
		$pincode_grid->setModel('tracker/PINCODEListing');
		$pincode_grid->addQuickSearch(array('state','city','post_office','pin_code'));
		$pincode_grid->addPaginator(10);
	}

	function page_mirc(){
		$this->add('H3')->set('Get MIRC/IFSC Code')->sub('Search via State, City, Bank, Branch, MIRC  or IFSC');
		$pincode_grid = $this->add('Grid');
		$pincode_grid->setModel('tracker/MIRCListing');
		$pincode_grid->addQuickSearch(array('state','city','bank','branch','mirc','ifsc'));
		$pincode_grid->addPaginator(10);
	}
}