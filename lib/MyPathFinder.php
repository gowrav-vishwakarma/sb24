<?php

class MyPathFinder extends PathFinder{
	    function loadClass($className){
        $origClassName = str_replace('-','',$className);

        /**/$this->api->pr->start('pathfinder/loadClass ');

        /**/$this->api->pr->next('pathfinder/loadClass/convertpath ');
        $className = ltrim($className, '\\');
        $nsPath = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $nsPath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        }
        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        /**/$this->api->pr->next('pathfinder/loadClass/locate ');
        try {
            if ($namespace){
                if (strpos($className,'page_')===0) {
                	// $nsPath = str_replace("page_", "", $nsPath);
                	// $origClassName = ltrim($origClassName,"page_");
                	// echo $nsPath;
                    $path=$this->api->locatePath(
                        'addons',
                        $nsPath.DIRECTORY_SEPARATOR.$classPath
                    );
                } else {
                    $path=$this->api->locatePath(
                        'addons',
                        $nsPath.DIRECTORY_SEPARATOR
                        .'lib'.DIRECTORY_SEPARATOR.$classPath
                    );
                }
            } else {
                if (strpos($className,'page_')===0) {
                    $path=$this->api->locatePath(
                        'page',
                        substr($classPath,5)
                    );
                } else {
                    $path=$this->api->locatePath(
                        'php',
                        $classPath
                    );
                }
            }
        }catch(PathFinder_Exception $e){
            $e
                ->addMoreInfo('class',$className)
                ->addMoreInfo('namespace',$namespace)
                ->addMoreInfo('orig_class',$origClassName)
                ;
            throw $e;
        }

        if(!is_readable($path)){
            throw new PathFinder_Exception('addon',$path,$prefix);
        }


        /**/$this->api->pr->next('pathfinder/loadClass/include ');
        /**/$this->api->pr->start('php parsing');
        include_once($path);
        /**/$this->api->pr->stop();
        if(!class_exists($origClassName ,false) && !interface_exists($origClassName, false))throw $this->exception('Class is not defined in file')
            ->addMoreInfo('file',$path)
            ->addMoreInfo('class',$className)
            ->addMoreInfo('origClassName',$origClassName);
        /**/$this->api->pr->stop();
    }

}