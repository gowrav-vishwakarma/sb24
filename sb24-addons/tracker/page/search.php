<?php

class page_tracker_page_search extends page_base_site {
	function page_index(){

		$this->add('View_ModuleHeading');//->set('Find STD Code')->sub('Search via State, City, Area or STD Code');
		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std',array('reset'=>1)),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode',array('reset'=>1)),'PIN Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc',array('reset'=>1)),'MIRC/IFSC');
		$mobile_tab = $tabs->addtabURL($this->api->url('./mobile'),'Mobile Career Tracer');
		$vehicle_tab = $tabs->addtabURL($this->api->url('./vehicle'),'Vehicle [RTO] Code');
		// $std_tab->setStyle('color','red');
	}

	function page_std(){

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('area');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$std_grid = $this->add('Grid');
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('tracker/STDState');
		$form->addField('dropdown','district_id','District')->setEmptyText("Any City")->setModel('tracker/STDDistrict');
		$distinct_area = $this->add('tracker/Model_STDListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$distinct_area->title_field = $distinct_area->id_field = 'area';
		$form->addField('dropdown','area')->setEmptyText("Any Area")->setModel($distinct_area);
		$form->add('Button')->set('Filter Search')->addStyle(array('margin-top'=>'25px','height'=>'30px'))->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id','area'=>'district_id'),'force_selection'=>true));

		if($form->isSubmitted()){
			$std_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['district_id'],
									'area'=>$form['area'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_STDListing');
		if($this->recall('filter',false)){

		if($this->recall('state',false))	$result->addCondition('state_id',$this->recall('state'));	
		if($this->recall('city',false)) $result->addCondition('district_id',$this->recall('city'));
		if($this->recall('area',false)) $result->addCondition('area',$this->recall('area'));
		}else{
			$result->addCondition('state_id',-1);	
		}
		
		$std_grid->setModel($result);
		// $std_grid->addQuickSearch(array('state','district','area','STD_code'));
		$std_grid->addPaginator(5);
	}

	function page_pincode(){
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('district');
			$this->forget('post_office');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("district",$_GET['district']?:$this->recall('district',false));
		$this->memorize("post_office",$_GET['post_office']?:$this->recall('post_office',false));
		
		$this->add('H3')->set('Find PIN Code')->sub('Search via State, City, Post Office or PIN Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$pincode_grid = $this->add('Grid');
		// $pincode_grid->addQuickSearch(array('state','district','post_office','pin_code'));
		
		$form->addField('dropdown','state_id','State')->setEmptyText("Any State")->setModel('tracker/PINCODEState');
		$form->addField('dropdown','district_id','District')->setEmptyText("Any City")->setModel('tracker/PINCODEDistrict');
		$post_office = $this->add('tracker/Model_PINCODEListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$post_office->title_field = $post_office->id_field = 'post_office';
		$form->addField('dropdown','post_office')->setEmptyText("Any Post Office")->setModel($post_office);
		$form->add('Button')->set('Filter Search')->addStyle(array('margin-top'=>'25px','height'=>'30px'))->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
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
		if($xfilter = $this->recall('filter',false)){
			if($xstate = $this->recall('state',false)) $result->addCondition('state_id',$xstate);
			if($xcity = $this->recall('district',false))$result->addCondition('district_id',$xcity);
			if($xpost_office = $this->recall('post_office',false)) $result->addCondition('post_office',$xpost_office);
		}else{
			$result->addCondition('state_id',-1);	
		}

		$pincode_grid->setModel($result,array('state','district','post_office','pin_code'));
		// $pincode_grid->addQuickSearch(array('state','district','post_office','pin_code'));
		$pincode_grid->addPaginator(10);
	}

	function page_mirc(){
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('bank');
			$this->forget('branch');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("bank",$_GET['bank']?:$this->recall('bank',false));
		$this->memorize("branch",$_GET['branch']?:$this->recall('branch',false));
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
		// $form->setFormClass('stacked atk-row');
  //           $o=$form->add('Order')
  //               ->move($form->addSeparator('noborder span4'),'first')
  //               ->move($form->addSeparator('noborder span4'),'after','city_id')
  //               ->move($form->addSeparator('noborder span3'),'after','branch')
  //               ->now();
		$submit_btn = $form->add('Button')->set('Filter Search')->addStyle(array('margin-top'=>'25px','margin-left'=>'30%','height'=>'30px'))->addClass('atk-form-row atk-form-row-dropdown span4');
		// $submit_btn->template->trySet('row_class','span12');
		$submit_btn->js('click')->submit();
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
		if($this->recall('filter',false)){
		if($this->recall('state',false)) $result->addCondition('state_id',$this->recall('state',false));
		if($this->recall('city',false)) $result->addCondition('city_id',$this->recall('city',false));
		if($this->recall('bank',false)) $result->addCondition('bank_id',$this->recall('bank',false));
		if($this->recall('branch',false)) $result->addCondition('branch',$this->recall('branch',false));
		}else{
			$result->addCondition('state_id',-1);	
		}

		$mirc_grid->add('H4',null,'top_1')->set('Search Result');
		$mirc_grid->setModel($result);
		// $mirc_grid->addQuickSearch(array('state','city','bank','branch','mirc','ifsc'));
		$mirc_grid->addPaginator(1);
	}

	function page_mobile(){
		
	}

	function page_vehicle(){
		$this->add('H3')->set('Get Vehicle RTO Code')->sub('Search via State, City, or Code');
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(2)->add('Text')->setHtml('&nbsp;');
		$col2=$cols->addColumn(8);
		$col3=$cols->addColumn(2);

		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'State','emptyText'=>'Please Select State'),
			'area_id'=>array('type'=>'dropdown','model'=>'Area','emptyText'=>'Please Select City'),
			);

		$chain_fields=array("area_id"=>'state_id');
		
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		// $form->setFormClass('stacked atk-row');
  //           $o=$form->add('Order')
  //               ->move($form->addSeparator('noborder span4'),'first')
  //               ->move($form->addSeparator('noborder span4'),'after','search')
  //               ->move($form->addSeparator('noborder span3'),'after','area_id')
  //               ->now();

		// $vehicle_grid = $this->add('Grid');
		
		
		// // $form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		// if(!$form->isSubmitted()){
		// 	$form->add('Controller_ChainSelector',array("chain_fields"=>array('area'=>'state_id'),'force_selection'=>true));
		// }

		// if($form->isSubmitted()){
				
		// }

		// $result = $this->add('tracker/Model_RTOListing');
		// if($_GET['filter']){
		// 	if($_GET['state']) $result->addCondition('state_id',$_GET['state']);
		// 	if($_GET['area']) $result->addCondition('area_id',$_GET['area']);
		// }
		// // else{
		// // $result->addCondition('state_id',-1);

		// // }

		// $vehicle_grid->add('H4',null,'top_1')->set('Search Result');
		// $vehicle_grid->setModel($result->debug(),array('state','area','name'));
		// // $vehicle_grid->addQuickSearch(array('state','area','name'));
		// $vehicle_grid->addPaginator(10);
	}
}