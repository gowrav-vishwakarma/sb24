<?php

class Menu extends Menu_Basic{
	function isCurrent($href){
        // returns true if item being added is current
        $href = explode("&", $href);
        $href=$href[0];
        if(!is_object($href))$href=str_replace('/','_',$href);
        return $href==$this->api->page||$href==';'.$this->api->page||$href.$this->api->getConfig('url_postfix','')==$this->api->page||(string)$href==(string)str_replace("/","_",$this->api->url());
    }
}