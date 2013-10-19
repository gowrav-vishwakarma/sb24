<?php
class page_index extends page_base_site {
	function init(){
		parent::init();
		$this->api->template->tryDel('top_advert');

	}
}