<?php
$page=Registry::$registry["PAGE"];
$idJob=$page->job_id;
?>
[*PAGE/SITE]
    [_TITLE]EDITOR SECCION - v.1.0 beta Smartclip[#]

    [_CONTENT]
<?php echo $idJob;?>
        [*:BEHAVIOR/CARD]
            [_:TITLE]Detalle del trabajo[#]
            [_:CONTENT]
                [*::WorkerFullList][#]
            [#]
        [#]
    [#]
[#]
