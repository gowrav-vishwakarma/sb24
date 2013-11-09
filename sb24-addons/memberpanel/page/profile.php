<?php

class page_memberpanel_page_profile extends page_memberpanel_page_base {
	function init(){
		parent::init();


		$this->add('H3')->set('Update your profile')
		->sub('SMS Authentication is needed for each updation, So first send your self an Verification Code')
		;

		$form=$this->add('Form');
		$model=$this->api->auth->model;
		$model->getElement('username')->system(true);
		$model->getElement('mobile_no')->system(true);
		$form->setModel($model,'base');
		
		// $sms_code=$form->addField('line','sms_code')->setFieldHint("Enter the code you received via sms");
		// $sms_btn = $form->getElement('mobile_no')->afterField()->add('ButtonSet')->add('Button')->set('Send Me Verification Code');

		// $mobile_field=$model->getElement('mobile_no')->system(true);
		$form->addField('password','re_password');

		// if($_GET['mobile_no']){
		// 	$model->sendCode($_GET['mobile_no']);
		// 	$sms_btn->set('Code Sent to '. $_GET['mobile_no']);
		// }
		// $sms_btn->js('click',$sms_btn->js()->reload(array('mobile_no'=>$mobile_field->js()->val())));
		

		$form->add('Button',null,null,array('view/mybutton','button'))->set('Save')->addStyle(array('margin-top'=>'5%','margin-left'=>'50%'))->addClass('shine1')->js('click')->submit();
			


		// if(!$form->isSubmitted()){
		// 	// $form->add('Controller_ChainSelector',array("chain_fields"=>array('city_id'=>'state_id'),'force_selection'=>false));
		// }
		$form->add('Order')
				->move('re_password','after','password')
				->now();

		if($form->isSubmitted()){

			// if($form->get('sms_code') != $model['update_code']){
			// 	$form->displayError("sms_code",'SMS Code is not correct');
			// }else{
			// 	if(strtotime($model['code_valid_till']) < strtotime(date('Y-m-d')) )
			// 	$form->displayError("sms_code",'SMS Code is expired, click on right button to send new code');
			// }
			
			if($form->get('password') != $form->get('re_password'))
				$form->displayError('re_password','Password must match');

			if($form->get('password') == "") $form->model->getElement('password')->destroy();

			$form->model['is_active']=true;
			$form->update();
			$form->js(null,$form->js()->reload())->univ()->successMessage("Your Information is stored")->execute();
		}


	}
}