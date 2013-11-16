<?php

class page_tracker_page_search extends page_base_site {
	function page_index(){

		$this->add('View_ModuleHeading')->set('Tracker')->sub('Search via State, City, Area or STD Code');
		$tabs = $this->add('Tabs');
		$std_tab = $tabs->addtabURL($this->api->url('./std',array('reset'=>1)),'STD');
		$pincode_tab = $tabs->addtabURL($this->api->url('./pincode',array('reset'=>1)),'PIN Code');
		$mirc_tab = $tabs->addtabURL($this->api->url('./mirc',array('reset'=>1)),'MICR / IFSC');
		$mobile_tab = $tabs->addtabURL($this->api->url('./mobile',array('reset'=>1)),'Mobile Tracker');
		$vehicle_tab = $tabs->addtabURL($this->api->url('./vehicle',array('reset'=>1)),'Vehicle [ RTO ] Code');
		// $std_tab->setStyle('color','red');
	}

	function page_std(){

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('area');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));
		$this->add('H3')->set('Find STD Code')->sub('Search by State, City, Area or STD Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$std_grid = $this->add('Grid');
		
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Any State");
		$state_field->template->trySet('row_class','span4');
		$state_field->setModel('tracker/STDState');
		$district_field=$form->addField('dropdown','district_id','District')->setEmptyText("Any City");
		$district_field->template->trySet('row_class','span4');
		$district_field->setModel('tracker/STDDistrict');

		$distinct_area = $this->add('tracker/Model_STDListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$distinct_area->title_field = $distinct_area->id_field = 'area';
		$area_field=$form->addField('dropdown','area')->setEmptyText("Any Area");
		$area_field->template->trySet('row_class','span4');
		$area_field->setModel($distinct_area);

		$search_field=$form->addField('line','search');
		$search_field->template->trySet('row_class','span9');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'20px','margin-left'=>'20px','font-size'=>'1.5em'))->addClass(' shine1')->js('click')->submit();
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                ->move($form->addSeparator('noborder atk-row'),'after','area')
                // ->move($form->addSeparator('noborder atk-row'),'after','search')
                ->now();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id','area'=>'district_id'),'force_selection'=>true));

		if($form->isSubmitted()){

			if($form['search'] == "" AND $form['area']=="")
				$form->displayError('area','Area is must to define');

			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('area');
			$this->forget('search');
			$std_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['district_id'],
									'area'=>$form['area'],
									'search'=>$form['search'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_STDListing');
		if($this->recall('filter',false)){

		if($this->recall('state',false))	$result->addCondition('state_id',$this->recall('state'));	
		if($this->recall('city',false)) $result->addCondition('district_id',$this->recall('city'));
		if($this->recall('area',false)) $result->addCondition('area',$this->recall('area'));


		
		if($search=$this->recall('search',false)){
				$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)')->system(true);
			// $result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','>','0');	
		}
		}else{
			$result->addCondition('state_id',-1);
		}
		
		$std_grid->setModel($result);
		$std_grid->addPaginator(50);
	}

	function page_pincode(){
		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('district');
			$this->forget('post_office');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("district",$_GET['district']?:$this->recall('district',false));
		$this->memorize("post_office",$_GET['post_office']?:$this->recall('post_office',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));
		
		$this->add('H3')->set('Find PIN Code')->sub('Search by State, City, Post Office or PIN Code');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$pincode_grid = $this->add('Grid');
		// $pincode_grid->addQuickSearch(array('state','district','post_office','pin_code'));
		
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Any State");
		$state_field->template->trySet('row_class','span4');
		$state_field->setModel('tracker/PINCODEState');
		$district_field=$form->addField('dropdown','district_id','District')->setEmptyText("Any City");
		$district_field->template->trySet('row_class','span4');
		$district_field->setModel('tracker/PINCODEDistrict');
		$post_office = $this->add('tracker/Model_PINCODEListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$post_office->title_field = $post_office->id_field = 'post_office';
		$post_field=$form->addField('dropdown','post_office')->setEmptyText("Any Post Office");
		$post_field->template->trySet('row_class','span4');
		$post_field->setModel($post_office);
		$search_field=$form->addField('line','search');
		$search_field->template->trySet('row_class','span9');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'20px','margin-left'=>'20px','font-size'=>'1.5em'))->addClass(' shine1')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('district_id'=>'state_id','post_office'=>'district_id'),'force_selection'=>true));

		if($form->isSubmitted()){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('district');
			$this->forget('post_office');
			$this->forget('search');
			$pincode_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'district'=>$form['district_id'],
									'post_office'=>$form['post_office'],
									'search'=>$form['search'],
									'filter'=>1
									)
								)->execute();	
		}

		$result = $this->add('tracker/Model_PINCODEListing');
		if($xfilter = $this->recall('filter',false)){
			if($xstate = $this->recall('state',false)) $result->addCondition('state_id',$xstate);
			if($xcity = $this->recall('district',false))$result->addCondition('district_id',$xcity);
			if($xpost_office = $this->recall('post_office',false)) $result->addCondition('post_office',$xpost_office);
		
			if($search=$this->recall('search',false)){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			// $result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','>','0');
		}
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
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("bank",$_GET['bank']?:$this->recall('bank',false));
		$this->memorize("branch",$_GET['branch']?:$this->recall('branch',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));
		$this->add('H3')->set('Get MICR / IFSC Code')->sub('Search by State, City, Bank, Branch, MICR  or IFSC');

		
		$form=$this->add('Form',null,null,array('form_horizontal'));

		$mirc_grid = $this->add('Grid');
		
		$state_field=$form->addField('dropdown','state_id','State')->setEmptyText("Any State");
		$state_field->setModel('State');
		$state_field->template->trySet('row_class','span3');
		$city_field=$form->addField('dropdown','city_id','City')->setEmptyText("Any City");
		$city_field->setModel('City');
		$city_field->template->trySet('row_class','span3');
		$bank_model=$this->add('tracker/Model_MIRCListing');
		$bank_model->title_field='bank';
		$bank_model->id_field='bank_id';
		$bank_field=$form->addField('dropdown','bank_id')->setEmptyText("Any Bank");
		$bank_field->setModel($bank_model);
		$bank_field->template->trySet('row_class','span3');
		$bank_branch = $this->add('tracker/Model_MIRCListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$bank_branch->title_field = $bank_branch->id_field = 'branch';
		$field_branch=$form->addField('dropdown','branch')->setEmptyText("Any Branch");
		$field_branch->setModel($bank_branch);
		$field_branch->template->trySet('row_class','span3');
		$search_field=$form->addField('line','search');
		$search_field->template->trySet('row_class','span9');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                // ->move($form->addSeparator('noborder atk-row'),'after','search')
                ->now();
		$submit_btn = $form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'20px','margin-left'=>'10px'))->addClass(' shine1')->js('click')->submit();
		// $submit_btn->template->trySet('row_class','span12');
		$submit_btn->js('click')->submit();
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','bank_id'=>'city_id','branch'=>array('bank_id','city_id'))));
			// $form->add('Controller_ChainSelector',array("chain_fields"=>array('bank_id','branch')));
		}

		if($form->isSubmitted()){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('bank');
			$this->forget('branch');
			$this->forget('search');
			$mirc_grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['city_id'],
									'bank'=>$form['bank_id'],
									'branch'=>$form['branch'],
									'search'=>$form['search'],
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
		
		if($search=$this->recall('search',false)){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE )');
			// $result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','>','0');
		} 
		}else{
			$result->addCondition('state_id',-1);	
		}

		$mirc_grid->add('H4',null,'top_1')->set('Search Result');
		$mirc_grid->setModel($result);
		// $mirc_grid->addQuickSearch(array('state','city','bank','branch','mirc','ifsc'));
		$mirc_grid->addPaginator(10);
	}

	function page_mobile(){
		if($_GET['reset']){
			$this->forget('search');
		}
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('H3')->set('Mobile Tracker Code')->sub('Search Mobile Number');
		$mobile_tracker_form=$this->add('Form');
		$mobile_tracker_form->addField('line','search')->template->trySet('row_class','span9');
		$mobile_tracker_form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->setStyle(array('margin-top'=>'20px','margin-left'=>'10px'))->addClass(' shine1')->js('click')->submit();
		
		$mobile_tracker_form->setFormClass('stacked atk-row');
            $o=$mobile_tracker_form->add('Order')
                ->move($mobile_tracker_form->addSeparator('noborder atk-row'),'first')
                // ->move($mobile_tracker_form->addSeparator('noborder atk-row'),'after','search')
                ->now();
		$mobile_tracker_grid=$this->add('Grid');

		$mobile_list=$this->add('tracker/Model_MobileListing');
		$mobile_list=$this->add('tracker/Model_MobileListing');
		
		$mobile_list->addExpression('company_logo')->set(function($m,$q){
			return $m->refSQL('company_id')->fieldQuery('company_logo');
		});

		 	
		

		if($search=$this->recall('search')){
			$model=$this->add('tracker/Model_MobileListing');
			$series = $model->_dsql()->del('field')->field('distinct(length(series)) l')->field('count(*)')->order('l desc')->group('l')->getAll();
			// print_r($series);
			foreach ($series as $s) {
				$mobile_list->addCondition('series',substr(trim($search), 0, $s['l']));
				$mobile_list->tryLoadAny();
				if($mobile_list->loaded()) break;
				$mobile_list->_dsql()->del('where');
			}

			if(!$mobile_list->loaded())
				$mobile_list->addCondition('id',-1);

		}
		else{
			$mobile_list->addCondition('series',-1);
		}
		$mobile_tracker_grid->setModel($mobile_list,array('state','company','company_logo'));

		$mobile_tracker_grid->addMethod('format_memberName',function($grid,$field)use($search){
			$m=$grid->add('Model_Member');
			$m->addCondition('mobile_no',$search);
			$m->tryLoadAny();
			if($m->loaded())
				$grid->current_row[$field] = $m['name'];
			else
				$grid->current_row[$field] = 'N/A';

		});

		$mobile_tracker_grid->addColumn('memberName','member_name');
		$mobile_tracker_grid->addFormatter('company_logo','picture');
 
		$mobile_tracker_grid->addPaginator(5);
		if($mobile_tracker_form->isSubmitted()){
			$this->forget('search');
			$mobile_tracker_grid->js()->reload(array('search'=>$mobile_tracker_form->get('search')))->execute();

		}
		
	}

	function page_vehicle(){

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('search');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('H3')->set('Get Vehicle RTO Code')->sub('Search by State, City or Code');
		// $cols=$this->add('Columns');
		// $col1=$cols->addColumn(2)->add('Text')->setHtml('&nbsp;');
		// $col2=$cols->addColumn(12);
		// $col3=$cols->addColumn(2);

		$rto_area = $this->add('tracker/Model_RTOListing');//->debug()->_dsql()->del('field')->field('distinct(area) area');
		$rto_area->title_field = $rto_area->id_field = 'area';

		$fields=array(
			'state_id'=>array('type'=>'dropdown','model'=>'tracker/RTOState','emptyText'=>'Please Select State','span'=>6),
			'city_id'=>array('type'=>'dropdown','model'=>$rto_area,'emptyText'=>'Please Select City','span'=>6),
			'search'=>array('type'=>'line','span'=>9)
			);

		$chain_fields=array("city_id"=>'state_id');
		
		$form = $this->add('SearchForm',array('fields'=>$fields,'chain_fields'=>$chain_fields));
		$form->getElement('search')->setAttr('placeholder','Serach Code Like RJ-27')->js(true)->_load('jquery.maskedinput.min')->mask('aa-99-aa-9999');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                ->move($form->addSeparator('noborder atk-row'),'after','city_id')
                ->now();

		$vehicle_grid = $this->add('Grid');
		
		
		// $form->add('Button')->set('Filter Search')->addStyle('margin-top','25px')->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id'),'force_selection'=>true));
		}

		if($form->isSubmitted()){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('search');

			$vehicle_grid->js()->reload(array('state'=>$form['state_id'],
													'city'=>$form['city_id'],
													'search'=>$form['search'],
													'filter'=>1))->execute();
				
		}

		$result = $this->add('tracker/Model_RTOListing');
		if($this->recall('filter',false)){
			
			if($this->recall('state',false)) $result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false)) $result->addCondition('area',$this->recall('city',false));

		if($search=$this->recall('search',false)){
			$result->addCondition('name','like',substr($search,0,5));
		}
		}
		else{
		$result->addCondition('state_id',-1);

		}

		$vehicle_grid->add('H4',null,'top_1')->set('Search Result');
		// $vehicle_grid->addQuickSearch(array('state','area','name'));
		$vehicle_grid->setModel($result,array('state','area','name'));
		$vehicle_grid->addPaginator(10);
	}
}