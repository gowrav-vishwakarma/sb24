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
		}

		$this->addSubmit('Search');
		if(!$this->isSubmitted())
			$this->add('Controller_ChainSelector',array('chain_fields'=>$this->chain_fields ,'force_selection'=>true));
	}

	function defaultTemplate(){
		return array('form_horizontal');
	}
}