<?php

class page_socialdirectory_page_search extends page_base_site {
	function init(){
		parent::init();

		if($_GET['reset']){
			$this->forget('search');
		}
		$this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('View_ModuleHeading');//->set('Find People/Persons')->sub('Search via State, City, Cast, Age or Interest');
		$register_btn = $this->add('Button')->set('Register Your self on SabKuch24.com ... And Expand your social coverage')->addClass('atk-row span12 btn')->setStyle(array('background'=>'green','color'=>'white','margin-bottom'=>'20px'));
		$register_btn->js('click',$this->js()->univ()->redirect('socialdirectory_page_member_index'));

		$form= $this->add('Form');
		$list = $this->add('socialdirectory/View_Lister');

		$form->addField('line','search')->setAttr('placeholder','like "Mr. Abc in udaipur from Xyz Cast Male"');

		$result = $this->add('Model_Member');

		if($form->isSubmitted()){
			
			$this->forget('search');

			if(strlen(trim($form['search']))<=3 )
				$form->displayError("search",'Worlds containing more then 3 characteres are used only');
			$list->js()->reload(array('search'=>$form['search']))->execute();
		}

		if($search=$this->recall('search',false)){
			$result->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN NATURAL LANGUAGE MODE)');
			$result->setOrder('Relevance','Desc');
			$result->addCondition('Relevance','>','0');
		}
		if(!$this->recall('search'))
			$result->addCondition('id','-1');

		$list->addPaginator(10);
		$list->setModel($result,'social');

	}
}