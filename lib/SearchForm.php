<?php


class SearchForm extends Form {
	public $fields=array();
	public $chain_fields=array();
	function init(){
		parent::init();

		foreach ($this->fields as $field => $options) {

			$f=$this->addField($options['type'],$field,ucwords(str_replace("_"," ",rtrim($field,'_id'))));
			if(isset($options['model'])) $f->setModel($options['model']);
			if(isset($options['emptyText'])) $f->setEmptyText($options['emptyText']);
			if(isset($options['mandatory'])) $f->validateNotNull();
			if(isset($options['valueList'])) $f->setValueList($options['valueList']);
			if(isset($options['span'])) $f->template->trySet('row_class','span'.$options['span']);
		}

		$this->addSubmit('Search',null,null,array('view/mybutton','button'))->setStyle('margin-top','20px');
		if(!$this->isSubmitted())
			$this->add('Controller_ChainSelector',array('chain_fields'=>$this->chain_fields ,'force_selection'=>true));
	}

	function defaultTemplate(){
		return array('view/myform');
	}
}