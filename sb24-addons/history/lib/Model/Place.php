<?php

namespace history;

class Model_Place extends \Model_Table {
	var $table= "place_listing";
	function init(){
		parent::init();

		$this->hasOne('State','state_id');
		$this->hasOne('City','city_id');
		$this->hasOne('Tehsil','tehsil_id');
		$this->hasOne('Area','area_id');
		$this->hasOne('history/PlaceType','placetype_id');
		$this->add("filestore/Field_Image","place_image_id")->type('image');
		$this->addField('name')->display(array('grid'=>'grid/inline'));
		$this->addField('short_description')->type('text')->display(array('grid'=>'shorttext,grid/inline'));
		$this->addField('about')->type('text')->display(array("form"=>"RichText",'grid'=>'shorttext,grid/inline'));
		$this->addField('search_string')->type('text')->system(true);
		$this->addHook('beforeSave',$this);
		$this->hasMany('history/Images','place_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$this['search_string']= $this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('area_id')->get('name'). " ".
								$this->ref('placetype_id')->get('name'). " ".
								$this["short_description"]. " ".
								$this['about']. " ".
								$this["name"];
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