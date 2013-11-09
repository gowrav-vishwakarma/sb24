<?php

class page_memberpanel_page_forgetpassword extends page_base_site {
	function page_index(){
		// parent::init();

		$this->add('H3')->set('Forgot Passssword !!!')->sub("Provide the following details to get your password at your mobile");
		$form=$this->add('Form');
		
		$form->addField('line','username');
		$question=$form->addField('dropdown','question','Select any question');
		$question->setModel('Questions');
		$form->addField('line','answer');
		$form->addSubmit('Send Code');

		if($form->isSubmitted()){
			$member=$this->add('Model_Member');
			$member->addCondition('username',$form['username']);
			$member->addCondition('question_id',$form['question']);
			$member->addCondition('answer',$form['answer']);
			$member->tryLoadAny();
			if(!$member->loaded())
				$form->displayError('username','Either username is not correct or this mobile number is not associated with this username');
			if($member->loaded())
				$form->js(null, $this->js()->univ()->frameURL('Enter New Passssword',$this->api->url('./newpassword',array('username'=>$form['username'],
														'question'=>$form['question'],'answer'=>$form['answer']))))->execute();


		}

	}

	function page_newpassword(){
		$this->api->stickyGET('username');
		$this->api->stickyGET('question');
		$this->api->stickyGET('answer');
		$member=$this->add('Model_Member');
		$member->addCondition('username',$_GET['username']);
		$member->addCondition('question_id',$_GET['question']);
		$member->addCondition('answer',$_GET['answer']);
		$member->tryLoadAny();

		if(!$member->loaded()){
			$this->add('View_Error')->set('Member not verified');
			return;
		}
		$form=$this->add('Form');
		$form->addField('password','password');
		$form->addField('password','re_password');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Update')->addStyle(array('margin-top'=>'25px','margin-left'=>'398px'))->addClass(' shine1')->js('click')->submit();
		
		if($form->isSubmitted()){
			if($form['password']!=$form['re_password'])
				$form->displayError('password','Password Must Match');
			$member['password']=$form['password'];
			$member->save();
			// $form->js()->univ()->closeDialog()->execute();
			$form->js()->univ()->redirect('memberpanel_page_dashboard')->execute();


		}

	}
}