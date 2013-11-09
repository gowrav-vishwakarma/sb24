<?php

class page_socialdirectory_page_search extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('search');
			$this->forget('cast');
			$this->forget('subcast');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));
		$this->memorize("cast",$_GET['cast']?:$this->recall('cast',false));
		$this->memorize("subcast",$_GET['subcast']?:$this->recall('subcast',false));

		$this->add('View_ModuleHeading')->set('Telephone Web Directory')->sub('Search via State, City, Cast, Age or Interest');
		$register_btn = $this->add('Button')->setHTML('<span class="shine"style="color:red; font-size:1.5em">*Click Here</span> Register Your self on SabKuch24.com ... And Expand your social coverage')->addClass('atk-row span12 btn')->setStyle(array('background'=>'green','color'=>'white','margin-bottom'=>'20px'));
		$register_btn->js('click',$this->js()->univ()->redirect('socialdirectory_page_member_index'));

		$form= $this->add('Form',null,null,array('form_horizontal'));
		$list = $this->add('socialdirectory/View_Lister');
		$field_state=$form->addField('dropdown','state_id','State')->setEmptyText('Please select State');
		$field_state->template->trySet('row_class','span2');
		$field_state->setModel('State');
		$field_city=$form->addField('dropdown','city_id','City')->setEmptyText('Please select City');
		$field_city->template->trySet('row_class','span2');
		$field_city->setModel('City');
		$field_tehsil=$form->addField('dropdown','tehsil_id','Tehsil')->setEmptyText('Please select Tehsil');
		$field_tehsil->template->trySet('row_class','span2');
		$field_tehsil->setModel('Tehsil');
		$field_area=$form->addField('dropdown','area_id','Area')->setEmptyText('Please select Area');
		$field_area->template->trySet('row_class','span2');
		$field_area->setModel('Area');
		$field_cast=$form->addField('dropdown','cast_id','Cast')->setEmptyText('Please select Cast');
		$field_cast->template->trySet('row_class','span2');
		$field_cast->setModel('socialdirectory/Cast');
		$field_subcast=$form->addField('dropdown','subcast_id','Sub Cast')->setEmptyText('Please select Sub Cast');
		$field_subcast->template->trySet('row_class','span2');
		$field_subcast->setModel('socialdirectory/SubCast');
		$search_field = $form->addField('line','search')->setAttr('placeholder','like "Mr. Abc in udaipur from Xyz Cast Male"');
		$search_field->template->trySet('row_class','span9');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                ->move($form->addSeparator('noborder atk-row'),'after','subcast_id')
                // ->move($form->addSeparator('noborder atk-row'),'after','search')
                ->now();


		$result = $this->add('Model_Member');
		if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array('chain_fields'=>array('city_id'=>'state_id','tehsil_id'=>'city_id','area_id'=>'tehsil_id','subcast_id'=>'cast_id'),'force_selection'=>true));
		}

		$form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'25px','margin-left'=>'20px'))->addClass(' shine1')->js('click')->submit();
		if($form->isSubmitted()){
			$this->forget('search');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('cast');
			$this->forget('subcast');
			// if(strlen(trim($form['search']))<=3 )
				// $form->displayError("search",'Worlds containing more then 3 characteres are used only');
			
			$list->js()->reload(array('search'=>$form['search'],
										'state'=>$form['state_id'],
										'city'=>$form['city_id'],
										'tehsil'=>$form['tehsil_id'],
										'area'=>$form['area_id'],
										'cast'=>$form['cast_id'],
										'subcast'=>$form['subcast_id'],
										'filter'=>'1'
										))->execute();
		}

		if($search=$this->recall('search',false)){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN NATURAL LANGUAGE MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','<>','0');
		}
		if(!$this->recall('filter'))
			$result->addCondition('id','-1');
		
		if($this->recall('filter',false)){
			if($this->recall('state',false))
					$result->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false))
					$result->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false))
					$result->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('area',false))
					$result->addCondition('area_id',$this->recall('area',false));
			if($this->recall('cast',false))
					$result->addCondition('cast_id',$this->recall('cast',false));
			if($this->recall('subcast',false))
					$result->addCondition('subcast',$this->recall('subcast',false));
		}

		$list->addPaginator(10);
		$list->setModel($result,'social');

	}
}