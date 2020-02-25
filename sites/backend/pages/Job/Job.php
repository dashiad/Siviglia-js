<?php
$page=Registry::$registry["PAGE"];
//var_dump($page);
$idJob=$page->job_id;
?>

[*PAGE/SITE]
    [_TITLE]EDITOR SECCION - v.1.0 beta Smartclip[#]

    [_CONTENT]    
        [*:BEHAVIOR/CARD]
            [_:TITLE]Detalle del trabajo: <?php echo $idJob; ?>[#]
            [_:CONTENT]
                [*::WorkerFullList][#]
            [#]
        [#]
    [#]
[#]
