define(
       ["dojo/_base/declare","dijit/_WidgetBase",
        "dijit/_TemplatedMixin","dijit/_WidgetsInTemplateMixin",
        "dojo/text!web//objects/model/objects/Page/actions/templates/EditAction.html","dojo/promise/all",
        "dojo/when","dojo/Deferred","Siviglia/forms/Form","dijit/form/Button"
        ],
       function(declare,WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,template,all,when,Deferred,Form)
       {
          return declare('.model.Page.EditAction',[WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,Form],{
                          templateString:template,
                          definition:{"NAME":"EditAction","MODEL":"\\model\\Page","ACTION":{"MODEL":"\\model\\web\\Page","ACTION":"EditAction","INHERIT":true},"ROLE":"Edit","REDIRECT":{"ON_SUCCESS":"","ON_ERROR":""},"INPUTS":{"id_site":{"PARAMS":{"LABEL":["id_site","host","canonical_url","hasSSL","namespace","websiteName"],"VALUE":"id_site","NULL_RELATION":[-1],"PRE_OPTIONS":{"-1":"Select an option"},"DATASOURCE":{"MODEL":"\\model\\web\\Site","NAME":"FullList","PARAMS":[]}}}},"INDEXFIELDS":{"id_page":{"REQUIRED":true,"FIELD":"id_page","MODEL":"\\model\\web\\Page"}}},                                 
                          title:"",
                          formClass:'',
                          description:'',
                          _widgetsInTemplate:true,
                          errors:[],
                           constructor:function()
                          {
                            this.inherited(arguments);
                          }
                        });
      });