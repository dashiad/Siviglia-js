<?php
    $page=\Registry::getService("page");
?>
[@DEPENDENCY]
    [_BUNDLE]Page[#]
    [_CONTENTS]
        [_WIDGET][_FILE]/sites/reflection/widgets/JSWIDGETS/Namespace.wid[#][#]

    [#]
[#]
[*/PAGE/PAGE]
    [_TITLE]Mi titulo[#]
    [_CONTENT]
        <div sivView="Reflection.Widgets.Namespace" sivParams='{"factoryClass":"pepe","namespace":"<?php echo $page->namespace;?>"}'>
        </div>
    [#]
[#]
