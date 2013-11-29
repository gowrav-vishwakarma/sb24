<?php

class page_businessdirectory_page_gallarypic extends page_base_site{
	function init(){
		parent::init();
		$gallay_pic_model=$this->add('businessdirectory/Model_GallaryImages');
		$gallay_pic_model->load($_GET['image_id']);
		$this->add('View')->setElement('img')->setAttr('src',$gallay_pic_model['gallary_img']);
	}
}