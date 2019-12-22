<?php
    $page=\Registry::getService("page");
?>

[*/PAGE/PAGE]
    [_TITLE]Mi titulo[#]
    [_CONTENT]
        <div sivView="Reflection.Widgets.Namespace" sivParams='{"factoryClass":"pepe","namespace":"<?php echo $page->namespace;?>"}'>
        </div>
    [#]
[#]
