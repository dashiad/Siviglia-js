define(
       ["dojo/_base/declare","dijit/_WidgetBase",
        "dijit/_TemplatedMixin","dijit/_WidgetsInTemplateMixin",
        "dojo/text!backoffice/Lang/objects/translations/actions/templates/EditAction.html","dojo/promise/all",
        "dojo/when","dojo/Deferred","Siviglia/forms/Form","dijit/form/Button"
        ],
       function(declare,WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,template,all,when,Deferred,Form)
       {
          return declare('Lang.translations.EditAction',[WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,Form],{
                          templateString:template,
                          definition:{"NAME":"EditAction","OBJECT":"Lang\\translations","ACTION":{"OBJECT":"\\backoffice\\Lang\\translations","ACTION":"EditAction","INHERIT":true},"ROLE":"Edit","REDIRECT":{"ON_SUCCESS":"","ON_ERROR":""},"INPUTS":[],"INDEXFIELDS":{"id_translation":{"REQUIRED":true,"FIELD":"id_translation","MODEL":"\\backoffice\\Lang\\translations"}}},
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