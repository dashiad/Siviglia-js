        define(
            ["dojo/_base/declare",
                "dojo/text!web//objects/model/objects/Page/dojo/views/templates/AdminView.html", "Siviglia/lists/DataSourceView","dojo/when", "dojo/Deferred", "dojo/dom-construct", "dojo/on","dijit/form/Select",
                "dijit/form/TextBox","dijit/form/CheckBox","dijit/layout/TabContainer","dijit/layout/ContentPane","dijit/form/RadioButton","dojo/query", "dijit/Dialog"
            ],
            function (declare,  template, DataSourceView, when, Deferred, dom, on) {
            return declare('.model.Page.AdminView', [DataSourceView],
            {
                templateString:template,
                modelName:'\\model\\Page',
                datasource:'AdminView',
                inputMappings:[],
                fieldDefinition:{"id_page":{"name":"id_page","display":true,"order":0,"label":"[@L]id_page[#]"},"tag":{"name":"tag","display":true,"order":1,"label":"[@L]tag[#]"},"id_site":{"name":"id_site","display":true,"order":2,"label":"[@L]id_site[#]"},"name":{"name":"name","display":true,"order":3,"label":"[@L]name[#]"},"date_add":{"name":"date_add","display":true,"order":4,"label":"[@L]date_add[#]"},"date_modified":{"name":"date_modified","display":true,"order":5,"label":"[@L]date_modified[#]"},"id_type":{"name":"id_type","display":true,"order":6,"label":"[@L]id_type[#]"},"isPrivate":{"name":"isPrivate","display":true,"order":7,"label":"[@L]isPrivate[#]"},"path":{"name":"path","display":true,"order":8,"label":"[@L]path[#]"},"title":{"name":"title","display":true,"order":9,"label":"[@L]title[#]"},"tags":{"name":"tags","display":true,"order":10,"label":"[@L]tags[#]"},"description":{"name":"description","display":true,"order":11,"label":"[@L]description[#]"}},
                groupable:[],
                addable:[],
                dataObjects: [{"MODEL":"\\model\\Page","INDEXFIELDS":["id_page"]}],
                // Deberia devolver informacion de estilo
                onRow:function(rowNode,row)
                {
                }
                
				/*,show_id_page:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_tag:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_site:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_name:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_date_add:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_date_modified:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_id_type:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_isPrivate:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_path:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_title:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_tags:function(object, value, node, options){ node.innerHTML=value;}*/
				/*,show_description:function(object, value, node, options){ node.innerHTML=value;}*/
            })
         });