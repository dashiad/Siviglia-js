<?php
$styleClass="";
$inputParams="";
$form=null;
?>
[*/FORMS/form({"object":"\\model\\web\\WebUser","layer":"web","name":"Login","form":"&$form"})]
    [_FORMERRORS]
        [_ERROR][#]
    [#]
    [_FORMGROUP]
        [_FIELDS]
            [*/FORMS/inputContainer({"name":"LOGIN","styleClass":"$styleClass"})]
                [_LABEL][@T][_ID]422560cb24964b0483b703a42bcd7500[#][_C]USUARIO[#][#][#]
                <?php $inputParams="";?>
                [_INPUT]
                    [*/types/inputs/Login({"name":"LOGIN","value":"LOGIN","params":"$inputParams","form":"$form"})][#]
                [#]
            [#]

            [*/FORMS/inputContainer({"name":"PASSWORD","styleClass":"$styleClass"})]
                [_LABEL][@T][_ID]230554a3a50cbfa648f233d46df9ca36[#][_C]CLAVE[#][#][#]
                <?php $inputParams="";?>
                [_INPUT]
                    [*/types/inputs/Password({"name":"PASSWORD","value":"PASSWORD","params":"$inputParams","form":"$form"})][#]
                [#]
            [#]
        [#]
    [#]

    [_FORMERRORS]
    [#]

    [_BUTTONS]
        [*:/INPUTS/Submit({"form":"&$form"})][_LABEL][@T][_ID]d1cdc7bc8e002ceb7405de577046402c[#][_C]Aceptar[#][#][#][#]
    [#]
[#]
