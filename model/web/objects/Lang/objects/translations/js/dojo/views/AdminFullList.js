        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Lang/objects/translations/views/templates/AdminFullList.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query", "dijit/Dialog"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Lang.translations.AdminFullList', [DataSourceView],
            {
                templateString:template,
                modelName:'Lang\\translations',
                datasource:'AdminFullList',
                inputMappings:[],
                fieldDefinition:{"VALUE":{"name":"VALUE","display":true,"order":0,"label":"[@L]VALUE[#]"},"lang":{"name":"lang","display":true,"order":1,"label":"[@L]lang[#]"},"id_string":{"name":"id_string","display":true,"order":2,"label":"[@L]id_string[#]"},"id_translation":{"name":"id_translation","display":true,"order":3,"label":"[@L]id_translation[#]"}},
                groupable:[],
                addable:[],
                dataObjects: [{"MODEL":"Lang\\translations","INDEXFIELDS":["id_lang"]}],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_VALUE:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_lang:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_string:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_translation:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });