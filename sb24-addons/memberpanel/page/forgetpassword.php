<?php

class page_memberpanel_page_forgetpassword extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Forgot Passssword !!!')->sub("Provide the following details to get your password at your mobile");
		$form=$this->add('Form');
		
		$form->addField('line','username');
		$form->addField('line','mobile_number');
		$form->addSubmit('Send Code');

		if($form->isSubmitted()){
			$member=$this->add('Model_Member');
			$member->addCondition('username',$form['username']);
			$member->addCondition('mobile_no',$form['mobile_number']);
			$member->tryLoadAny();
			if(!$member->loaded())
				$form->displayError('username','Either username is not correct or this mobile number is not associated with this username');

			$member->sendCode();
			$form->js()->univ()->closeDialog()->execute();

		}

	}
}