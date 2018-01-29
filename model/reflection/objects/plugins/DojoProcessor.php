<?php
namespace model\reflection\Js\Dojo;

class DojoProcessor extends \model\reflection\base\SystemPlugin
{

    function __construct()
    {
    }
       
    function SAVE_SYSTEM($level)
    {
        if($level!=1)return;
        printPhase("Generando codigo Dojo");
        $this->iterateOnModels('createOutput');
    }
    function createOutput($layer,$objName,$model)
    {
        include_once(PROJECTPATH."/model/reflection/Js/objects/Dojo/DojoGenerator.php");
        $dojoClass=new \model\reflection\Js\dojo\DojoGenerator($model);
        $dojoClass->save();
        
    }
}
