<?php $site=\Registry::$registry["site"]; ?>
[@DEPENDENCY]
    [_BUNDLE]Global[#]
    [_CONTENTS]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/node_modules/jquery/dist/jquery.js[#][#]

        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/css/jdesktop.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.min.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.structure.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.theme.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/jdesktop.forms.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/jdesktop.text.css"[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/style.css[#][#]

        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/js/vendor/jquery-ui.min.js[#][#]
[_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/js/vendor/jquery.scrollTo-min.js[#][#]
[_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/js/vendor/jquery.ui.selectmenu.js[#][#]


        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/themes/redmond/theme.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/js/jdesktop.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/njdesktop/js/jdesktop.widgets.js[#][#]

        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/jqwidgets/jqx-all.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/jqwidgets/globalization/globalize.js[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/jqwidgets/styles/jqx.base.css[#][#]
        [_CSS][_URL]##STATICS_WEB_ROOT##/packages/jqwidgets/styles/jqx.light.css[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/Siviglia/Siviglia.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/Siviglia/SivigliaStore.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/Siviglia/SivigliaTypes.js[#][#]
        [_SCRIPT][_URL]##STATICS_WEB_ROOT##/packages/Siviglia/Model.js[#][#]

        [_SCRIPT]
            [_CODE]
            <script>
                var Siviglia=Siviglia || {};
                Siviglia.config={
                    baseUrl:'<?php echo $site->getCanonicalUrl();?>/',
                    publicUrl:'<?php echo $site->getCanonicalUrl();?>',
                    namespaces:['backoffice','web'],
                    defaultNamespace:'backoffice',
                    jsFramework:'jquery',
                    datasourcePrefix:'datasources',
                    isDevelopment:0,
                    mapper: 'Siviglia',
                    id_lang:'es'
                };
                Siviglia.Model.initialize(Siviglia.config);
                $(document).ready(function(){
                    var parser=new Siviglia.UI.HTMLParser();
                    parser.parse($(document.body));
                });
            </script>
            [#]
        [#]
    [#]
[#]
[*DESKTOP/INDEX]
    [_TITLE]Index SECCION - v.1.0 beta Smartclip[#]
    [_CONTENT]
    <div data-sivView="Siviglia.site.widgets.JS.Desktop"></div>
    [#]
[#]


