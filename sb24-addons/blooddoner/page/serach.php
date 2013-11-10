<?php

class page_blooddoner_page_serach extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('blood_group');
		}
		$this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		$this->memorize("state",$_GET['state']?:$this->recall('state',false));
		$this->memorize("city",$_GET['city']?:$this->recall('city',false));
		$this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		$this->memorize("area",$_GET['area']?:$this->recall('area',false));

		$this->add('View_ModuleHeading')->set('Find Blood Dooner')->sub('Search via State, City, Type of event and date');
		$register_btn = $this->add('Button')->setStyle('padding','15px')->addClass('shine')->setHTML('<span class="shine"style="color:red; font-size:1em">*Click Here</span>&nbsp; Register Your self on SabKuch24.com ... And Expand your social coverage')->addClass('atk-row span12 btn')->setStyle(array('background'=>'green','color'=>'white','margin-bottom'=>'20px','padding'=>'10px'));
		$register_btn->js('click',$this->js()->univ()->redirect('memberpanel_page_register'));

		$form = $this->add('Form',null,null,array('form_horizontal'));
		$form->addClass('stacked');
		$form->template->trySet('fieldset','atk-row');
		$state_field = $form->addField('dropdown','state_id','State')->setEmptyText("Any State");
		$state_field->template->trySet('row_class','span2');
		$state_field->setModel('State');
		$city_field = $form->addField('dropdown','city_id','City')->setEmptyText("Any City");
		$city_field->template->trySet('row_class','span2');
		$city_field->setModel('City');
		$tehsil_field = $form->addField('dropdown','tehsil_id','Tehsil')->setEmptyText("Any Tehsil");
		$tehsil_field->template->trySet('row_class','span2');
		$tehsil_field->setModel('Tehsil');
		$area_field = $form->addField('dropdown','area_id','Area')->setEmptyText("Any Area");
		$area_field->template->trySet('row_class','span2');
		$area_field->setModel('Area');

		$blood_group_field = $form->addField('dropdown','blood_group')->setValueList(array("A+"=>'A+',"A-"=>'A-',"B+"=>'B+',"B-"=>'B-',"O+"=>'O+',"O-"=>'O-',"AB+"=>'AB+',"AB-"=>'AB-'))->setEmptyText("Any Blood Group");//->validateNotNull();
		$blood_group_field->template->trySet('row_class','span3');

		$search_field = $form->addField('line','search')->setAttr('placeholder','Filter your search with Area, City, State etc Information');
		$search_field->template->trySet('row_class','span9');
		// $search_field->afterField()->add('Button')->set('GO')->js('click',$form->js()->submit());
		// $form->addSubmit('Search');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Search')->addStyle(array('margin-top'=>'25px','margin-left'=>'20px'))->addClass(' shine1')->js('click')->submit();

		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row'),'first')
                ->move($form->addSeparator('noborder atk-row'),'after','blood_group')
                // ->move($form->addSeparator('noborder atk-row'),'after','search')
                ->now();
         if(!$form->isSubmitted()){
			$form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id','tehsil_id'=>'city_id','area_id'=>'tehsil_id'),'force_selection'=>true));
		}

		$grid=$this->add('Grid');

		$doners = $this->add('blooddoner/Model_Listing');
		$member_join = $doners->join('social_member','member_id');
		$member_join->addField('address');
		$member_join->addField('search_string');
		$member_join->addField('mobile_no');

		$doners->addCondition('want_to_donate',true);

		if($this->recall('filter',false)){

			if($this->recall('state',false)) $doners->addCondition('state_id',$this->recall('state',false));
			if($this->recall('city',false)) $doners->addCondition('city_id',$this->recall('city',false));
			if($this->recall('tehsil',false)) $doners->addCondition('tehsil_id',$this->recall('tehsil',false));
			if($this->recall('area',false)) $doners->addCondition('area_id',$this->recall('area',false));
			if($this->recall('blood_group',false)) $doners->addCondition('blood_group',$_GET['blood_group']);
			if($search=$this->recall('search',false)) {
				$doners->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
				$doners->setOrder('Relevance','Desc');
				$doners->addCondition('Relevance','>','0.8');
				}
			}else{
				$doners->addCondition('id','-1');

			}

		$grid->setModel($doners);
		$grid->removeColumn('search_string');
		$grid->removeColumn('Relevance');

		if($form->isSubmitted()){
			$this->forget('filter');
			$this->forget('state');
			$this->forget('city');
			$this->forget('tehsil');
			$this->forget('area');
			$this->forget('blood_group');
			$this->forget('search');
			$grid->js()->reload(array(
									'state'=>$form['state_id'],
									'city'=>$form['city_id'],
									'tehsil'=>$form['tehsil_id'],
									'area'=>$form['area_id'],
									'blood_group'=>$form['blood_group'],
									'search'=>$form['search'],
									'filter'=>1))->execute();
		}

	}
}