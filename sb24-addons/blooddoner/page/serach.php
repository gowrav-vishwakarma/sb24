<?php

class page_blooddoner_page_serach extends page_base_site {
	function init(){
		parent::init();

		$this->add('View_ModuleHeading');
		$register_btn = $this->add('Button')->set('Register Your self as a Blood Doner... This may save a life')->addClass('atk-row span12 btn')->setStyle(array('background'=>'green','color'=>'white'));
		$register_btn->js('click',$this->js()->univ()->redirect('memberpanel_page_register'));

		$form = $this->add('Form');
		$form->addClass('stacked');
		$form->template->trySet('fieldset','atk-row');
		$blood_group_field = $form->addField('dropdown','blood_group')->setValueList(array("A+"=>'A+',"A-"=>'A-',"B+"=>'B+',"B-"=>'B-',"O+"=>'O+',"O-"=>'O-',"AB+"=>'AB+',"AB-"=>'AB-'))->setEmptyText("Any Blood Group");//->validateNotNull();
		$blood_group_field->template->trySet('row_class','span2');
		$search_field = $form->addField('line','search')->setAttr('placeholder','Filter your search with Area, City, State etc Information');
		$search_field->template->trySet('row_class','span10');
		// $search_field->afterField()->add('Button')->set('GO')->js('click',$form->js()->submit());
		// $form->addSubmit('Search');
		$form->add('Button')->set('Filter Search')->addStyle(array('margin-top'=>'25px','height'=>'30px'))->addClass('atk-form-row atk-form-row-dropdown span3')->js('click')->submit();



		$grid=$this->add('Grid');

		$doners = $this->add('blooddoner/Model_Listing');
		$member_join = $doners->join('social_member','member_id');
		$member_join->join('state','state_id')->addField('state','name');
		$member_join->join('city','city_id')->addField('city','name');
		$member_join->addField('address');
		$member_join->addField('search_string');
		$member_join->addField('mobile_no');

		$doners->addCondition('want_to_donate',true);

		if($_GET['blood_group'] OR $_GET['filter']){
			if($_GET['blood_group']) $doners->addCondition('blood_group',$_GET['blood_group']);
			if($_GET['filter']) {
				$doners->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$_GET['filter'].'" IN BOOLEAN MODE)');
				$doners->setOrder('Relevance','Desc');
				$doners->addCondition('Relevance','>','0.8');
				}
			}else{
				$doners->addCondition('blood_group','1');

			}

		$grid->setModel($doners);
		$grid->removeColumn('search_string');
		$grid->removeColumn('Relevance');

		if($form->isSubmitted()){
			$grid->js()->reload(array('blood_group'=>$form['blood_group'],'filter'=>$form['search']))->execute();
		}

	}
}