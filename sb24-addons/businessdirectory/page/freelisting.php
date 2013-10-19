<?php

class page_businessdirectory_page_freelisting extends page_base_site {
	function page_index(){
		$a=$this->add('View')->setElement('a')
				->setAttr('href','#')->set('Free Registration')->addClass('ui-widget ui-widget-header ui-corener-all ui-button');
		$a->js('click')->univ()->frameURL('Free Listing',$this->api->url('./form'));
	}

	function page_form(){
		$form = $this->add('businessdirectory/Form_FreeListing');
		$form->setModel('businessdirectory/FreeListing');
	}
}