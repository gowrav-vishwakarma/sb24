<?php

namespace history;

class Model_Place extends \Model_Table {
	var $table= "place";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Area','area_id');
		$this->hasOne('history/PlaceType','placetype_id');
		$this->addField('name');
		$this->addField('short_description')->type('text')->display(array('grid'=>'shorttext'));
		$this->addField('about')->type('text')->display(array("form"=>"RichText",'grid'=>'shorttext'));
		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_model=$this->add('history/Model_Place');
		$old_model->addCondition('name',$this['name']);
		$old_model->addCondition('id','<>',$this->id);
		$old_model->tryLoadAny();
		if($old_model->loaded())
			throw $this->exception("This Place is Allready Exist, Take Another.. ",'ValidityCheck')->setField('name');
			
		if($this['state_id']=="") throw $this->exception('State is must','ValidityCheck')->setField('state_id');
		if($this['city_id']=="") throw $this->exception('City is must','ValidityCheck')->setField('city_id');
		if($this['placetype_id']=="") throw $this->exception('Place type is must','ValidityCheck')->setField('placetype_id');

	}
}