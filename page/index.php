<?php
class page_index extends page_base_site {
	function init(){
		parent::init();

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('content_class');
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(1)->add('View')->setHtml('&nbsp');
		$col2=$cols->addColumn(5)->addStyle(array("margin-top"=>"150px","margin-left"=>"450px"));
		$col3=$cols->addColumn(2);
		$col2->add('HtmlElement')->setElement('img')->setAttr('src','logo.png')->setAttr('align','center');
		$form=$col2->add('Form')->addClass('stacked');
		$form->addField('line','Search','');

	}
}