<?php
class page_index extends page_base_site {
	function init(){
		parent::init();

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('content_class');
		// $cols=$this->add('Columns');
		// $col1=$cols->addColumn(1)->add('View')->setHtml('&nbsp');
		// $col2=$cols->addColumn(5);//->addStyle(array("margin-top"=>"150px","margin-left"=>"450px"));
		// $col3=$cols->addColumn(2);
		$this->add('HtmlElement')->setElement('img')->setAttr('src',$this->api->sb24_config['front_image'])->setAttr('align','center');
		$form=$this->add('Form')->addClass('stacked');
		$form->addField('line','search','');
		if($form->isSubmitted()){
			$form->js()->univ()->redirect($this->api->url('businessdirectory_page_search',array('reset'=>1,"filter"=>1,'search'=>$form['search'])))->execute();
		}

	}

	function defaultTemplate(){
		return array('page/index');
	}
}