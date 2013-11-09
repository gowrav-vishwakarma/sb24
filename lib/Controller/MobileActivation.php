<?php

class Controller_MobileActivation extends AbstractController{
	function init(){
		parent::init();
		
		$this->owner->addField('ActivationCode')->system(true);
		$this->owner->addField('is_activated')->type('boolean')->defaultValue(false)->system(true);
		$this->owner->addField('activated_on')->type('date')->system(true);

		if(!$this->owner->hasElement('mobile_no'))
			$mobile_field = $this->owner->addField('mobile_no');
		else
			$mobile_field = $this->owner->getElement('mobile_no');

		$mobile_field->mandatory('Mobile Number is must');
		$mobile_field->hint('An Activation code will be sent to this number');

		$this->owner->addHook('beforeInsert',function($model){
			$code = $model['ActivationCode']=rand(10000,99999);
			$model->add('Controller_SMS')->sendActivationCode($model,$code);
		});

		$this->owner->addMethod('resendActivationCode',function($model){
			$code = $model['ActivationCode'] = rand(10000,99999);
			$model->add('Controller_SMS')->sendActivationCode($model,$code);
		});

		$this->owner->addMethod('activate',function($model){
			$model['is_activated'] = true;
			$model['activated_on'] = date('Y-m-d H:i:s');
			$model->save();
		});
	}
}