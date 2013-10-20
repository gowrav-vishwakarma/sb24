<?php

class page_businessdirectory_page_search extends page_base_site {
	function init(){
		parent::init();
		
		$form=$this->add('Form');
		$form->addClass('stacked');
		$search_field=$form->addField('line','search')->setAttr('placeholder','Type business related query here');
		$filter=$form->addSeparator('atk-row filter');
		$form->addField('dropdown','state')->template->trySet('row_class','span3');
		$form->addField('dropdown','city')->template->trySet('row_class','span3');
		$form->addField('dropdown','city')->template->trySet('row_class','span3');
		$form->addField('dropdown','city')->template->trySet('row_class','span3');
		
		$filter->js(true)->_selector('.filter')->hide();
		$search_field->afterField()->add('Button')->set('Filter')->js('click',$filter->js()->_selector('.filter')->toggle('slow'));

		$business_listing = $this->add('businessdirectory/View_Listing');
		$business_listing->setModel('businessdirectory/Listing');

	}
}