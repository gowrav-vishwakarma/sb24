<?php
class page_test extends Page {
	function init(){
		parent::init();
		$form=$this->add('Form');
		$form->addField('line','age');
		$view->$this->add('View');

		$member=$this->add('Model_Member');
		if($_GET['age']){
			$member->addCondition('age',$_GET['age']);
			foreach ($member as $junk) {
				$v=$view->add('View_MyView');
				$v->setModel($member);
				
			}

		if($form->isSubmitted()){
			$form->js(null,$view->js()->relaod())->relaod(array('age'=>$form->get('age')))->execute();
		}

	}
}
}