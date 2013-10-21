<?php

class page_businessdirectory_page_search extends page_base_site {
	function init(){
		parent::init();
		
		$search_tabs = $this->add('Tabs');
		$search_tab= $search_tabs->addTab('Search Business');
		$filter_tab= $search_tabs->addTab('Advance Search');
		$form=$search_tab->add('Form');
		$form->addClass('stacked');
		$search_field=$form->addField('line','search')->setAttr('placeholder','Type business related query here');
		
		$business_listing = $this->add('businessdirectory/View_Listing');
		$result = $this->add('businessdirectory/Model_Listing');

		if($_GET['string']){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$_GET['string'].' WITH QUERY EXPANSION")');
			$result->setOrder('Relevance','Desc');
			// $result->addCondition('Relevance','>','0.5');
		}

		$business_listing->setModel($result);
		if($form->isSubmitted()){
			$business_listing->js()->reload(array('string'=>$form->get('search')))->execute();
		}

	}
}