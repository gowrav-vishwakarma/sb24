<?php

namespace adds;

class Model_Ad extends \Model_Table {
	var $table= "ad";
	function init(){
		parent::init();

		$this->hasOne('adds/AdBlock');
		$this->add('filestore/Field_Image','ad_image_id');//->type('files')->display(array('grid'=>'picture'));
		$this->addField('click_url');
		$this->addField('created_on')->type('date')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('valid_till')->type('date');//->defaultValue(date('Y-m-d',strtotime(date('Y-m-d H:i:s')). " +1 year"));
		$this->addField('last_renewal_on')->type('date')->defaultValue(date('Y-m-d H:i:s'));

		$this->addField('company_name');
		$this->addField('contact_person');
		$this->addField('mobile_number');
		$this->addField('add_charge')->hint('Finalised amount for this add');
		
		$this->addExpression('received_payment')->set(function($m,$q){
			return $m->refSQL('adds/AdPayment')->sum('amount');
		});


		$this->hasMany('adds/AdPayment','ad_id');




		$this->add('dynamic_model/Controller_AutoCreator');
	}
}