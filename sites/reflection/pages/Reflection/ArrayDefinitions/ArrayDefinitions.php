<?php
    $page=\Registry::getService("page");
?>
[*/PAGE/PAGE]
    [_TITLE]Mi titulo[#]
    [_CONTENT]
        <div data-sivView="Reflection.Widgets.DefinitionForm"></div>
    [#]
[#]
[@DEPENDENCY]
    [_BUNDLE]Page[#]
    [_CONTENTS]
        [_WIDGET][_FILE]/sites/statics/html/packages/Siviglia/autoui/AutoUI.wid[#][#]
        [_WIDGET][_FILE]/sites/reflection/widgets/JSWIDGETS/DefinitionEditor.wid[#][#]
    [#]
[#]
