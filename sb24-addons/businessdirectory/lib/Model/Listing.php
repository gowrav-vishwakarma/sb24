<?php

namespace businessdirectory;

class Model_Listing extends \Model_Table {
	var $table= "business_listing";
	function init(){
		parent::init();

		//Has One ...

		$member = $this->hasOne('Member','member_id');
		if($this->api->auth->model)
			$member->defaultValue($this->api->auth->model->id);

		$this->hasOne('Category','category_id')->caption('Industry')->group('free');
		$this->hasOne('SubCategory','subcategory_id')->caption('Major Field')->group('free');//->display(array('form'=>'autocomplete/Plus'));
		
		$this->hasOne('State','state_id')->group('free');
		$this->hasOne('City','city_id')->group('free');
		$this->hasOne('Area','area_id')->group('free');

		//Basic Details FREE LISTING SECTION
		$this->addField('name')->caption('Name Of Company')->group('free');
		$this->addField('company_address')->type('text')->group('free');
		$this->addField('mobile_no')->group('free');
		$this->addField('company_ph_no')->group('free');
		$this->addField('address')->type('text')->group('free');
		$this->addField('short_description')->type('text')->group('free');
		$this->addField('email_id')->group('free');
		$this->addField('website')->group('free');
		$this->addField('tags')->type('text')->group('free');

		// Paid Informations
		$this->addField('about_us')->type('text')->group('paid');
		$this->addField('contact_person')->group('paid');
		$this->addField('designation')->setValueList(array(	
															'proprietor'=>'Proprietor',
															'partner'=>'Partner',
															'director'=>'Director',
															'authorized-person'=>'Authorized-Person'
															))->group('paid');
		$this->addField('contact_person_contact_number')->group('paid');
		
		//Images
			//FREE LISTING IMAGES & INFO
			$this->add("filestore/Field_Image","company_logo_id")->type('image')->group('free');	
			//PAID LISTING IMAGES & INFO
				// GALLERY PICS
			$this->add("filestore/Field_Image","gallery_image_1_id")->type('image')->group('paid');
			$this->addField('gallery_image_1_info')->group('paid');
			$this->add("filestore/Field_Image","gallery_image_2_id")->type('image')->group('paid');
			$this->addField('gallery_image_2_info')->group('paid');
			$this->add("filestore/Field_Image","gallery_image_3_id")->type('image')->group('paid');
			$this->addField('gallery_image_3_info')->group('paid');
			$this->add("filestore/Field_Image","gallery_image_4_id")->type('image')->group('paid');
			$this->addField('gallery_image_4_info')->group('paid');
			$this->add("filestore/Field_Image","gallery_image_5_id")->type('image')->group('paid');
			$this->addField('gallery_image_5_info')->group('paid');
				// PRODUCTS & SERVICES IMAGES & INFO
			$this->add("filestore/Field_Image","products_image_1_id")->type('image')->group('paid');
			$this->addField('products_image_1_info')->group('paid');
			$this->add("filestore/Field_Image","products_image_2_id")->type('image')->group('paid');
			$this->addField('products_image_2_info')->group('paid');
			$this->add("filestore/Field_Image","products_image_3_id")->type('image')->group('paid');
			$this->addField('products_image_3_info')->group('paid');
			$this->add("filestore/Field_Image","products_image_4_id")->type('image')->group('paid');
			$this->addField('products_image_4_info')->group('paid');
			$this->add("filestore/Field_Image","products_image_5_id")->type('image')->group('paid');
			$this->addField('products_image_5_info')->group('paid');

		$this->addField('map_latitute_longitude')->group('paid');

		// System & Admin Fields
		$this->addField('created_on')->type('date')->defaultValue(date('Y-m-d'))->system(true);
		$this->addField('valid_till')->type('date')->defaultValue(date('Y-m-d',strtotime('+1 Year')))->system(true);
		$this->addField('last_paid_on')->type('date')->defaultValue(date('Y-m-d'))->system(true);
		$this->addField('is_paid')->type('boolean')->defaultValue(false);
		$this->addField('is_active')->type('boolean')->defaultValue(false);

		// SEARCH
		$this->addField('search_string')->system(true);

		$this->addExpression('payment_received')->set(function($m,$q){
			return $m->refSQL('businessdirectory/PayAmount')->sum('name');
		});

		//Has Many Relations		

		$this->hasMany('businessdirectory/RegisteredCategory','listing_id');
		$this->hasMany('businessdirectory/PayAmount','listing_id');
		
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		$this['search_string']= $this->ref('category_id')->get('name') . " ".
								$this->ref('subcategory_id')->get('name') . " ".
								$this->ref('state_id')->get('name') . " ".
								$this->ref('city_id')->get('name'). " ".
								$this->ref('area_id')->get('name'). " ".
								$this["tags"]. " ".
								$this['name']. " ".
								$this["company_address"]. " ".
								$this['contact_person']
							;

		if($this['category_id']=="") throw $this->exception('Category is Must','ValidityCheck')->setField('category_id'); 
		if($this['subcategory_id']=="") throw $this->exception('Sub Category is Must','ValidityCheck')->setField('subcategory_id'); 
		if($this['state_id']=="") throw $this->exception('State is Must','ValidityCheck')->setField('state_id'); 
		if($this['city_id']=="") throw $this->exception('City is Must','ValidityCheck')->setField('city_id'); 
		// if($this['area_id']=="") throw $this->exception('Area is Must','ValidityCheck')->setField('area_id'); 

		if($this->api->auth->model AND $this->api->auth->model['is_staff'] AND !$this['member_id']){
			// Create member from contact person information first
			// username => email of listing //password => random number
			// this_listing's member_id = new member saved
			$member=$this->add('Model_Member');
			$member['name']=$this['contact_person'];
			$member['username']=$this['email_id'];
			$member['pasword']=rand(1000,9999);
			$member['mobile_no']=$this['mobile_no'];
			$member['state_id']=$this['state_id'];
			$member['city_id']=$this['city_id'];
			$member->save();
			$this['member_id']=$member->id;

		}

	}

	function beforeDelete(){
		if($this['is_paid'] AND $this->api->auth->model['is_staff']==false)
			$this->api->js()->univ()->errorMessage("This is Paid Entry, You cannot delete, Contact SabKuch24 Office")->execute();
	}

	function markActivate(){
        $this->set('is_active',!$this->get('is_active'))->update();
	}
}