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
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$std_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('State');
		$form->addField('dropdown','city_id','City')->setEmptyText("Any City")->setModel('City');
		$distinct_area = $this->add('tracker/Model_STDListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$distinct_area->title_field = $distinct_area->id_field = 'area';
		$form->addField('dropdown','area')->setEmptyText("Any Area")->setModel($distinct_area);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('state_id','city_id','area')));

		if($form->isSubmitted()){
			$std_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['city_id'],
									'area'=>$form['area'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_STDListing');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['city']) $result->addCondition('city_id',$_GET['city']);
		if($_GET['area']) $result->addCondition('area',$_GET['area']);
		
		$std_grid->setModel($result);
		$std_grid->addQuickSearch(array('state','city','area','STD_code'));
		$std_grid->addPaginator(10);
	}

	function page_pincode(){
		$this->add('H3')->set('Find PIN Code')->sub('Search via State, City, Post Office or PIN Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$pincode_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('State');
		$form->addField('dropdown','city_id','City')->setEmptyText("Any City")->setModel('City');
		$post_office = $this->add('tracker/Model_PINCODEListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$post_office->title_field = $post_office->id_field = 'post_office';
		$form->addField('dropdown','post_office')->setEmptyText("Any Post Office")->setModel($post_office);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('state_id','city_id','post_office')));

		if($form->isSubmitted()){
			$pincode_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['city_id'],
									'post_office'=>$form['post_office'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_PINCODEListing');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['city']) $result->addCondition('city_id',$_GET['city']);
		if($_GET['post_office']) $result->addCondition('post_office',$_GET['post_office']);

		$pincode_grid->setModel($result);
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