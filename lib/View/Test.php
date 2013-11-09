<?php

class View_Test extends Lister{
	function defaultTemplate(){
		return array('view/mylister');
	}

	function formatRow(){
		$this->model->ref('Entities')->count()->getOne();
		$this->current_row['entities']=($this->current_row['age']>18)?"Y":"N";
		parent::formatRow();
	}

}

$this->add('View_Test')->setModel('XYZ');

<div id="<?$_name?>">
	<?name?><?/name?>
	<?age?><?/age?>
	<?can_vote?><?/can_vote?>
</div>
<?$Content?>