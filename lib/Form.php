<?php

class Form extends Form_Basic{
	function addSubmit($label='Save',$name=null,$spot='form_buttons',$template='button'){
        $submit = $this->add('Form_Submit',$name,$spot,$template)
            ->setLabel($label)
            ->setNoSave();

        return $submit;
    }
}