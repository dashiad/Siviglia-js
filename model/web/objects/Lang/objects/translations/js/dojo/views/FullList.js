        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Lang/objects/translations/views/templates/FullList.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query", "dijit/Dialog"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Lang.translations.FullList', [DataSourceView],
            {
                templateString:template,
                modelName:'Lang\\translations',
                datasource:'FullList',
                inputMappings:[],
                fieldDefinition:{"id_translation":{"name":"id_translation","display":true,"order":0,"label":"[@L]id_translation[#]"},"id_string":{"name":"id_string","display":true,"order":1,"label":"[@L]id_string[#]"},"lang":{"name":"lang","display":true,"order":2,"label":"[@L]lang[#]"},"realm":{"name":"realm","display":true,"order":3,"label":"[@L]realm[#]"},"dirty":{"name":"dirty","display":true,"order":4,"label":"[@L]dirty[#]"},"translated":{"name":"translated","display":true,"order":5,"label":"[@L]translated[#]"},"original":{"name":"original","display":true,"order":6,"label":"[@L]original[#]"}},
                groupable:[],
                addable:[],
                dataObjects: [{"MODEL":"Lang\\translations","INDEXFIELDS":["id_lang"]}],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_translation:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_string:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_lang:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_realm:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_dirty:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_translated:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_original:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });