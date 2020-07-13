        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Sites/objects/WebsiteUrls/views/templates/AdminView.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Sites.WebsiteUrls.AdminView', [DataSourceView],
            {
                templateString:template,
                modelName:'Sites\\WebsiteUrls',
                datasource:'AdminView',
                inputMappings:[],
                fieldDefinition:{"id_websiteUrl":{"name":"id_websiteUrl","display":true,"order":0,"label":"[@L]id_websiteUrl[#]"},"id_website":{"name":"id_website","display":true,"order":1,"label":"[@L]id_website[#]"},"url":{"name":"url","display":true,"order":2,"label":"[@L]url[#]"},"priority":{"name":"priority","display":true,"order":3,"label":"[@L]priority[#]"}},
                groupable:[],
                addable:[],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_websiteUrl:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_website:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_url:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_priority:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });