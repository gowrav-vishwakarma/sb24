<?php

class Menu extends Menu_Basic{
	function addMenuItem($page,$label=null){
        if(!$label){
            $label=ucwords(str_replace('_',' ',$page));
        }
        $id=$this->name.'_i'.count($this->items);
        $label=$this->api->_($label);
        $js_page=null;
        if($page instanceof jQuery_Chain){
            $js_page="#";
            $this->js('mouseover',$page)->_selector('#'.$id);
            $this->js('mouseout','$(".ui-dialog-content").dialog("close")')->_selector('.ui-dialog-content');
            $page=$id;
        }
        $this->items[]=array(
            'id'=>$id,
            'page'=>$page,
            'href'=>$js_page?:$this->api->url($page),
            'label'=>$label,
            $this->class_tag=>$this->isCurrent($page)?$this->current_menu_class:$this->inactive_menu_class,
        );
        return $this;
    }

	function isCurrent($href){
        // returns true if item being added is current
        $href = explode("&", $href);
        $href=$href[0];
        if(!is_object($href))$href=str_replace('/','_',$href);
        return $href==$this->api->page||$href==';'.$this->api->page||$href.$this->api->getConfig('url_postfix','')==$this->api->page||(string)$href==(string)str_replace("/","_",$this->api->url());
    }
}