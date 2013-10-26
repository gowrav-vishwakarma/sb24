<?php

class page_tracker_page_search extends page_base_site {
	function page_index(){

		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std'),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode'),'Pin Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc'),'MIRC/IFSC');
		$mobile_tab = $tabs->addtabURL($this->api->url('./mobile'),'Mobile Career Tracer');
		$vehicle_tab = $tabs->addtabURL($this->api->url('./vehicle'),'Vehicle [RTO] Code');

	}

	function page_std(){
		$this->add('View_ModuleHeading')->set('Find STD Code')->sub('Search via State, City, Area or STD Code');
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$std_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('tracker/STDState');
		$form->addField('dropdown','district_id','District')->setEmptyText("Any City")->setModel('tracker/STDDistrict');
		$distinct_area = $this->add('tracker/Model_STDListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$distinct_area->title_field = $distinct_area->id_field = 'area';
		$form->addField('dropdown','area')->setEmptyText("Any Area")->setModel($distinct_area);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id','area'=>'district_id'),'force_selection'=>true));

		if($form->isSubmitted()){
			$std_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'district'=>$form['district_id'],
									'area'=>$form['area'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_STDListing');
		if($_GET['state']){
			$result->addCondition('state_id',$_GET['state']);	
		}else{
			// $result->addCondition('state_id',-1);	
		}
		if($_GET['district']) $result->addCondition('district_id',$_GET['district']);
		if($_GET['area']) $result->addCondition('area',$_GET['area']);
		
		$std_grid->setModel($result);
		$std_grid->addQuickSearch(array('state','district','area','STD_code'));
		$std_grid->addPaginator(10);
	}

	function page_pincode(){
		$this->add('H3')->set('Find PIN Code')->sub('Search via State, City, Post Office or PIN Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$pincode_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('tracker/PINCODEState');
		$form->addField('dropdown','district_id','District')->setEmptyText("Any City")->setModel('tracker/PINCODEDistrict');
		$post_office = $this->add('tracker/Model_PINCODEListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$post_office->title_field = $post_office->id_field = 'post_office';
		$form->addField('dropdown','post_office')->setEmptyText("Any Post Office")->setModel($post_office);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id','post_office'=>'district_id'),'force_selection'=>true));

		if($form->isSubmitted()){
			$pincode_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'district'=>$form['district_id'],
									'post_office'=>$form['post_office'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_PINCODEListing');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['district']) $result->addCondition('district_id',$_GET['district']);
		if($_GET['post_office']) $result->addCondition('post_office',$_GET['post_office']);

		$pincode_grid->setModel($result,array('state','district','post_office','pin_code'));
		$pincode_grid->addQuickSearch(array('state','district','post_office','pin_code'));
		$pincode_grid->addPaginator(10);
	}

	function page_mirc(){
		$this->add('H3')->set('Get MIRC/IFSC Code')->sub('Search via State, City, Bank, Branch, MIRC  or IFSC');

		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$mirc_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('State');
		$form->addField('dropdown','city_id','City')->setEmptyText("Any City")->setModel('City');
		$bank_model=$this->add('tracker/Model_MIRCListing');
		$bank_model->title_field='bank';
		$bank_model->id_field='bank_id';
		$form->addField('dropdown','bank_id')->setEmptyText("Any Bank")->setModel($bank_model);
		$bank_branch = $this->add('tracker/Model_MIRCListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$bank_branch->title_field = $bank_branch->id_field = 'branch';
		$form->addField('dropdown','branch')->setEmptyText("Any Branch")->setModel($bank_branch	);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','bank_id'=>'city_id','branch'=>array('bank_id','city_id'))));
			// $form->add('Controller_ChainSelector',array("chain_fields"=>array('bank_id','branch')));
		}

		if($form->isSubmitted()){
			$mirc_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['city_id'],
									'bank'=>$form['bank_id'],
									'branch'=>$form['branch'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_MIRCListing');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['city']) $result->addCondition('city_id',$_GET['city']);
		if($_GET['bank']) $result->addCondition('bank_id',$_GET['bank']);
		if($_GET['branch']) $result->addCondition('branch',$_GET['branch']);

		$mirc_grid->add('H4',null,'top_1')->set('Search Result');
		$mirc_grid->setModel($result);
		$mirc_grid->addQuickSearch(array('state','city','bank','branch','mirc','ifsc'));
		$mirc_grid->addPaginator(10);
	}

	function page_mobile(){
		
	}

	function page_vehicle(){
		$this->add('H3')->set('Get Vehicle RTO Code')->sub('Search via State, City, or Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$vehicle_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('tracker/RTOState');
		$area = $this->add('tracker/Model_RTOListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$area->title_field = $area->id_field = 'area';
		$form->addField('dropdown','area')->setEmptyText("Any Area")->setModel($area);
		$form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('area'=>'state_id'),'force_selection'=>true));
		}

		if($form->isSubmitted()){
			$vehicle_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'area'=>$form['area'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_RTOListing');
		if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		if($_GET['area']) $result->addCondition('area',$_GET['area']);

		$vehicle_grid->add('H4',null,'top_1')->set('Search Result');
		$vehicle_grid->setModel($result,array('state','area','name'));
		$vehicle_grid->addQuickSearch(array('state','area','name'));
		$vehicle_grid->addPaginator(10);
	}
}