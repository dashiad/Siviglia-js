        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Sites/objects/WebsiteCountries/views/templates/View.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Sites.WebsiteCountries.View', [DataSourceView],
            {
                templateString:template,
                modelName:'Sites\\WebsiteCountries',
                datasource:'View',
                inputMappings:[],
                fieldDefinition:{"id_websiteCountry":{"name":"id_websiteCountry","display":true,"order":0,"label":"[@L]id_websiteCountry[#]"},"id_website":{"name":"id_website","display":true,"order":1,"label":"[@L]id_website[#]"},"id_country":{"name":"id_country","display":true,"order":2,"label":"[@L]id_country[#]"}},
                groupable:[],
                addable:[],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_websiteCountry:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_website:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_country:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });