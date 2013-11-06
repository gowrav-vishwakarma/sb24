<?php
class page_index extends page_base_site {
	function init(){
		parent::init();

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('content_class');
		$this->api->template->tryDel('body_margin');
		

		$this->add('HtmlElement')->setElement('img')->setAttr('src',$this->api->sb24_config['front_image'])->setAttr('align','center')->setStyle('margin-bottom','10px');
		$search_form=$this->add('Form')->addClass('stacked');
		$search_form->addField('line','search','')->validateNotNull("Please Search")->setClass('highlight span10');
		$search_form->add('Button')->set('Search')->addClass('shine atk-form-row atk-form-row-dropdown  ')->js('click')->submit();
		$search_form->add('View')->set('A Rajasthan Local Search Engine & Telephone Web Directory')->setStyle(array('margin-top'=>'20px',
																												'font-family'=> 'Snippet','sans-serif',' font-weight'=>' bold','color'=>'saddlebrown'));

		if($search_form->isSubmitted()){
			$search_form->js()->univ()->redirect($this->api->url('businessdirectory_page_search',array('reset'=>1,"filter"=>1,'search'=>$search_form['search'])))->execute();
		}

		$cols=$this->add('Columns');
		$col_login1=$cols->addColumn(2)->setHTML('&nbsp;');
		$col_login=$cols->addColumn(8)->addClass('right-front-page-col');
		$col_login2=$cols->addColumn(1);
		// $col_register=$cols->addColumn(7)->addClass('col');


		// $col_register->add('H3')->set('Register Now, Its Free ...')
		// 	->sub('Add your own free listings, get unlimited informations ... ')->setStyle('color','burlywood');

		// $model=$this->add('Model_Member');
		// $model->getElement('password')->system(true);

		// $form_register = $col_register->add('Form');
		// $form_register->setModel($model,'base');
		// $form_register->addSubmit("Register")->addClass('shine');

		// if($form_register->isSubmitted()){
		// 	$form_register->update();
		// 	$form_register->js(null, $this->js()->univ()->redirect('memberpanel_page_dashboard'))->univ()->closeDialog()->execute();
		// }


		$col_login->add('H3')->set('Login to Your Account')->setStyle('color','burlywood');
		$login_form=$col_login->add('Form');
		$login_form->addField('line','username');
		$login_form->addField('password','password');
		$register_btn= $login_form->addButton('Register')->addClass('shine');

		// $register_btn=$auth->add('Button')->set('Register Now Free')->addClass('atk-row span12');
		$register_btn->js('click')->univ()->frameURL('Register Your Self',$this->api->url('memberpanel_page_register'));


		$login_form->addSubmit('Login')->addClass('shine');
		$col_login->add('H5')->set('Forgot Password')->setStyle('text-decoration','underline')->addStyle('cursor','help')->js('click',$this->js()->univ()->frameURL("Forgot Password !!!",$this->api->url('memberpanel_page_forgetpassword')));

		if($login_form->isSubmitted()){
			$this->api->auth->setModel('Member','username','password');
			if(!$this->api->auth->verifyCredentials($login_form['username'],$login_form['password'])){
				$login_form->displayError('password','Incorrect login information');
			}
			$this->api->auth->loginBy('username',$login_form['username']);
			$this->js()->univ()->redirect('memberpanel_page_dashboard')->execute();
		}
	}


	function defaultTemplate(){
		return array('page/index');
	}
}