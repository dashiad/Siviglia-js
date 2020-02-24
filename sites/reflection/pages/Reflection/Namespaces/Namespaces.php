<?php
    $page=\Registry::getService("page");
?>

[*/PAGE/PAGE]
    [_TITLE]Mi titulo[#]
    [_CONTENT]
        <div data-sivView="Reflection.Widgets.Namespace" data-sivParams='{"factoryClass":"pepe","namespace":"<?php echo $page->namespace;?>"}'>
        </div>
    [#]
[#]
