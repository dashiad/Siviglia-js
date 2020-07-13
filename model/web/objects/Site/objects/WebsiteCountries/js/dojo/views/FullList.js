        define(
            ["dojo/_base/declare",
                "dojo/text!backoffice/Sites/objects/WebsiteCountries/views/templates/FullList.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('Sites.WebsiteCountries.FullList', [DataSourceView],
            {
                templateString:template,
                modelName:'Sites\\WebsiteCountries',
                datasource:'FullList',
                inputMappings:[],
                fieldDefinition:{"id_country":{"name":"id_country","display":true,"order":0,"label":"[@L]id_country[#]"},"name":{"name":"name","display":true,"order":1,"label":"[@L]name[#]"},"id_state":{"name":"id_state","display":true,"order":2,"label":"[@L]id_state[#]"}},
                groupable:[],
                addable:[],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_websiteCountry:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });