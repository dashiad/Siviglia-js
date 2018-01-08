        define(
            ["dojo/_base/declare",
                "dojo/text!web//objects/model/objects/Site/dojo/views/templates/AdminView.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query", "dijit/Dialog"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('.model.Site.AdminView', [DataSourceView],
            {
                templateString:template,
                modelName:'\\model\\Site',
                datasource:'AdminView',
                inputMappings:[],
                fieldDefinition:{"id_site":{"name":"id_site","display":true,"order":0,"label":"[@L]id_site[#]"},"host":{"name":"host","display":true,"order":1,"label":"[@L]host[#]"},"canonical_url":{"name":"canonical_url","display":true,"order":2,"label":"[@L]canonical_url[#]"},"hasSSL":{"name":"hasSSL","display":true,"order":3,"label":"[@L]hasSSL[#]"},"namespace":{"name":"namespace","display":true,"order":4,"label":"[@L]namespace[#]"},"websiteName":{"name":"websiteName","display":true,"order":5,"label":"[@L]websiteName[#]"}},
                groupable:[],
                addable:[],
                dataObjects: [{"MODEL":"\\model\\Site","INDEXFIELDS":["id_website"]}],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_site:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_host:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_canonical_url:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_hasSSL:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_namespace:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_websiteName:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });