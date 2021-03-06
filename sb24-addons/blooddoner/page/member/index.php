	<?php


class page_blooddoner_page_member_index extends page_memberpanel_page_base {
	function init(){
		parent::init();

		$form = $this->add('Form');
		$model = $form->setModel($this->api->auth->model->ref('blooddoner/Listing'))->setOrder('id')->tryLoadAny();

		if(!$model->loaded()) $model->save();

		// $sms_code=$form->addField('line','sms_code');
		// $sms_btn = $sms_code->afterField()->add('ButtonSet')->add('Button')->set('Send Me Verification Code');

		// if($_GET['send_code']){
		// 	$model->sendCode();
		// 	$sms_btn->set('Code Sent to '. $model->ref('member_id')->get('mobile_no'));
		// }
		// $sms_btn->js('click',$sms_btn->js()->reload(array('send_code'=>'yes')));

		$form->getElement('blood_group')->validateNotNull();
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Update My Info')->addStyle(array('margin-top'=>'25px','font-size'=>'18px','margin-left'=>'275px'))->addClass(' shine1')->js('click')->submit();
		if(!$form->isSubmitted())
			$form->add('Controller_ChainSelector',array('chain_fields'=>array('area_id'=>'tehsil_id','tehsil_id'=>'city_id','city_id'=>'state_id')));

		if($form->isSubmitted()){

			// if($form->get('sms_code') != $model['update_code']){
			// 	$form->displayError("sms_code",'SMS Code is not correct');
			// }else{
			// 	if(strtotime($model['code_valid_till']) < strtotime(date('Y-m-d')) )
			// 	$form->displayError("sms_code",'SMS Code is expired, click on right button to send new code');
			// }
			$form->model['is_active']=true;
			$form->update();
			$form->js()->univ()->successMessage("Your Information is stored")->execute();
		}

	}
}