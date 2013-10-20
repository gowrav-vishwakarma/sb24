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
		
		// $filter=$form->addSeparator('atk-row filter');
		// $form->addField('checkboxlist','search_in')->setValueList(array('state','city'))->set('0,1,2');
		// $filter->js(true)->_selector('.filter')->hide();
		// $search_field->afterField()->add('Button')->set('Filter')->js('click',$filter->js()->_selector('.filter')->toggle('slow'));

		$business_listing = $this->add('businessdirectory/View_Listing');
		$business_listing->setModel('businessdirectory/Listing');

	}
}