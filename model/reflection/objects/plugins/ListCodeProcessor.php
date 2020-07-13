<?php
namespace lib\reflection\plugins;

class ListCodeProcessor extends \model\reflection\base\SystemPlugin {

    function REBUILD_VIEWS($level)
    {
        if($level!=1)return;
        printPhase("Generando plantillas de Listas");
        $this->iterateOnModels("buildViews");
    }
    function buildViews($layer,$name,$model,$onlyNonAdmin=false)
    {
        $datasources=$model->getDataSources();
        
        foreach($datasources as $dKey=>$dValue)
        {
            $role=$dValue->getRole();
            if($dValue->isAdmin() && $onlyNonAdmin)
                continue;
            if($role=="view")
            {

                $curGenerator=new \model\reflection\Html\views\ViewWidget($dValue->getName(),$model,$dValue);                
                if($curGenerator->mustRebuild())                
                {
                    $curGenerator->initialize();
                    $curGenerator->generateCode();
                    $curGenerator->save();
                }
                
            }
            else
            {
                $curGenerator=new \model\reflection\Html\views\ListWidget($dValue->getName(),$model,$dValue);
                if($curGenerator->mustRebuild())
                {
                    $curGenerator->initialize();
                    $curGenerator->generateCode($dValue->isAdmin());
                    $curGenerator->save();
                }
            }
        }
    }
}

