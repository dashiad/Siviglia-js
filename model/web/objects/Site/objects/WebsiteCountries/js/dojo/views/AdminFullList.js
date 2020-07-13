        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Sites/objects/WebsiteCountries/views/templates/AdminFullList.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Sites.WebsiteCountries.AdminFullList', [DataSourceView],
            {
                templateString:template,
                modelName:'Sites\\WebsiteCountries',
                datasource:'AdminFullList',
                inputMappings:[],
                fieldDefinition:{"id_websiteCountry":{"name":"id_websiteCountry","display":true,"order":0,"label":"[@L]id_websiteCountry[#]"}},
                groupable:[],
                addable:[],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_websiteCountry:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });