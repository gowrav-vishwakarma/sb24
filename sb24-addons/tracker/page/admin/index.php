<?php

class page_tracker_page_admin_index extends Page {
	function page_index(){

		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std'),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode'),'Pin Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc'),'MIRC /IFSC');
		$mobile_tab = $tabs->addTab('Mobile');
		$vehicle_tab = $tabs->addtabURL($this->api->url('./vehicle'),'Vehicle [RTO] Code');

		$mobiletabs=$mobile_tab->add('Tabs');
		$company=$mobiletabs->addTab('Mobile Company');
		$listing=$mobiletabs->addTab('Mobile Listing');
		$company_crud=$company->add('CRUD');
		$company_crud->setModel('tracker/MobileCompany');
		if($company_crud->grid){
			$company_crud->grid->addPaginator(10);
			$company_crud->grid->addQuickSearch(array('name'));
			
		}
		$listing_crud=$listing->add('CRUD');
		$listing_crud->setModel('tracker/MobileListing');
		if($listing_crud->grid){
			$listing_crud->grid->addPaginator(10);
			$listing_crud->grid->addQuickSearch(array('series'));
			
		}



	}

	function page_std(){

		$tabs = $this->add('Tabs');

		$listing_tab = $tabs->addTab('STD Code Entries');
		$district_tab = $tabs->addTab('District Entries');
		$state_tab = $tabs->addTab('State Entries');;

		$std_crud = $listing_tab->add('CRUD');
		$std_crud->setModel('tracker/STDListing');
		$std_crud->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id')));

		if($std_crud->grid) $std_crud->grid->addPaginator(50);
		if($std_crud->grid) $std_crud->grid->addQuickSearch(array('area'));

		$district_crud = $district_tab->add('CRUD');
		$district_crud->setModel('tracker/STDDistrict');
		if($district_crud->grid) $district_crud->grid->addPaginator(50);
		if($district_crud->grid) $district_crud->grid->addQuickSearch(array('area','name','state','district'));

		$state_crud = $state_tab->add('CRUD');

		$state_crud->setModel('tracker/STDState');


	}
	
	function page_pincode(){
		$pincode_crud = $this->add('CRUD');
		$pincode_crud->setModel('tracker/PINCODEListing');
		$pincode_crud->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id')));
		if($pincode_crud->grid){
			
			$pincode_crud->grid->addPaginator(50);
			$pincode_crud->grid->addQuickSearch(array('pin_code','district'));
		}
	}

	function page_mirc(){
		$mirc_crud = $this->add('CRUD');
		$mirc_crud->setModel('tracker/MIRCListing');
		$mirc_crud->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id')));
	}

	function page_vehicle(){
		$vehicle_crud = $this->add('CRUD');
		$vehicle_crud->setModel('tracker/RTOListing');
		// $vehicle_crud->add('Controller_ChainSelector',array("chain_fields"=>array('area'=>'state_id')));
		if($vehicle_crud->grid)
			$vehicle_crud->grid->addPaginator(10);
	}


}