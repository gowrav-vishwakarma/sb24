<?php
/**
 * Consult documentation on http://agiletoolkit.org/learn 
 */
class Frontend extends ApiFrontend {
    var $menu;
    
    function init(){
        parent::init();
        // Keep this if you are going to use database on all pages
        $this->dbConnect();
        $this->requires('atk','4.2.0');

        // This will add some resources from atk4-addons, which would be located
        // in atk4-addons subdirectory.

        // $this->addLocation('.',array('addons'=>'sb24-addons'));
        // $this->addLocation('sb24-addons',array(
        //             'page'=>array(
        //                 'page',
        //                 'misc/lib',
        //                 )
        //             ))
        //     ->setParent($this->pathfinder->base_location);

        $this->addLocation('atk4-addons',array(
                    'php'=>array(
                        'mvc',
                        'misc/lib',
                        )
                    ))
            ->setParent($this->pathfinder->base_location);

        // A lot of the functionality in Agile Toolkit requires jUI
        $this->add('jUI');

        // Initialize any system-wide javascript libraries here
        // If you are willing to write custom JavaScript code,
        // place it into templates/js/atk4_univ_ext.js and
        // include it here
        $this->js()
            ->_load('atk4_univ')
            ->_load('ui.atk4_notify')
            ;

        // If you wish to restrict access to your pages, use BasicAuth class
        $this->auth=$this->add('BasicAuth');
        $this->auth->setModel('Staff','username','password')

            // ->allow('demo','demo')
            // use check() and allowPage for white-list based auth checking
            // ->check()
            ;

        // This method is executed for ALL the pages you are going to add,
        // before the page class is loaded. You can put additional checks
        // or initialize additional elements in here which are common to all
        // the pages.

        // Menu:

            $this->menu=$this->add('Menu',null,'Menu');

        // If you are using a complex menu, you can re-define
        // it and place in a separate class


        

    }
}
