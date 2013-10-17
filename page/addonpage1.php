<?php

$temp =explode('\\', $_GET['addon_page']);

$tc='class page_addonpage extends '.$temp[0]."\\page_".$temp[1] .'{
	function init(){
		parent::init();
		$this->api->stickyGET("addon_page");
	}

}';
eval($tc);