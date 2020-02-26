<?php
$page=Registry::$registry["PAGE"];
$idJob=$page->job_id;
?>

[*PAGE/JOB]
    [_TITLE]Jobs: Backend - SmartClip v.1.0 beta[#]

    [_CONTENT]    
        [*:BEHAVIOR/CARD]
            [_:TITLE]Detalle del trabajo: <?php echo $idJob; ?>[#]
            [_:CONTENT]
                [*::WorkerFullList][#]
            [#]
        [#]
    [#]
[#]
