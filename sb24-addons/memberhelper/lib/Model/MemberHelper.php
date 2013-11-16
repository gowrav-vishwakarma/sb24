<?php
namespace memberhelper;
class Model_MemberHelper extends \Model_Table {
	var $table= "member_helper";
	function init(){
		parent::init();
		$this->hasOne('Member','member_id');
		$this->addField('name','Title');
		$this->addField('message')->type('text');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d:h:i:s'));
		$this->addField('status')->enum(array('Open','Running','Closed'))->defaultValue('Open');
		$this->addField('last_accessed_on')->type('date')->defaultValue(date('Y-m-d:h:i:s'));
		$this->add('filestore/Field_Image','document_1_id')->type('image');
		$this->add('filestore/Field_Image','document_2_id')->type('image');
		$this->add('filestore/Field_Image','document_3_id')->type('image');
		$this->add('filestore/Field_Image','document_4_id')->type('image');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}