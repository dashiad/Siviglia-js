<?php
namespace lib\reflection\plugins;

class CodeGenerator extends \model\reflection\base\SystemPlugin {


    function SAVE_SYSTEM($sys,$level)
    {
        if( $level!=2 )
            return;

        printPhase("Generando Clases Modelo");
        // Comienza la generacion de controladores.Por cada una de las acciones, y de las vistas, hay que generar
        // codigo de 1) chequeo de estado, 2) chequeo de permisos.
        // Hay que cargar la clase controladora existente, compararla con el codigo generado, y hacer un merge con la clase existente.
        global $Config;
        for($kk=0;$kk<count($Config["PACKAGES"]);$kk++) {
            $package = $Config["PACKAGES"][$kk];

            printSubPhase("Generando modelos de ".$package);
            $objs=$sys->objectDefinitions[$package];
            foreach($objs as $objName=>$modelDef)
            {

                printItem("Generando $objName");
                $modelClass=$sys->classes[$package][$objName]["MODEL"];
                $modelClass->generate();
            }

        }
    }
}

?>
