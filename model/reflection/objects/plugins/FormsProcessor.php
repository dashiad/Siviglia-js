<?php
namespace model\reflection\Action;

class FormsProcessor extends \model\reflection\base\SystemPlugin {
    // Se tienen que generar formularios para dos cosas:
    // 1 : Acciones del modelo
    // 2 : Filtros de datasources
    // 3 : Formularios custom

    function REBUILD_ACTIONS($level)
    {
       
        if( $level!=2 )return;
        printPhase("Generando Formularios sobre acciones");
        $this->iterateOnModels("buildForms");
    }
    function buildForms($layer,$objName,$modelDef)
    {
        printSubPhase("Generando formularios de ".$objName);
                
        $actions=$modelDef->getActions();        
        foreach($actions as $name=>$action)
        {                    
            $formInstance=new \model\reflection\Html\forms\FormDefinition($name,$action);
            if($formInstance->mustRebuild())
                $formInstance->create();
            else
                $formInstance->initialize();
             
             $formInstance->saveDefinition();
             $formInstance->generateCode();
        }
                    
            
     }

}

?>
