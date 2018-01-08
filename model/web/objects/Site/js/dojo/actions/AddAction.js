define(
       ["dojo/_base/declare","dijit/_WidgetBase",
        "dijit/_TemplatedMixin","dijit/_WidgetsInTemplateMixin",
        "dojo/text!web//objects/model/objects/Site/actions/templates/AddAction.html","dojo/promise/all",
        "dojo/when","dojo/Deferred","Siviglia/forms/Form","dijit/form/Button"
        ],
       function(declare,WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,template,all,when,Deferred,Form)
       {
          return declare('.model.Site.AddAction',[WidgetBase,TemplatedMixin,WidgetsInTemplateMixin,Form],{
                          templateString:template,
                          definition:{"NAME":"AddAction","MODEL":"\\model\\Site","ACTION":{"MODEL":"\\model\\web\\Site","ACTION":"AddAction","INHERIT":true},"ROLE":"Add","REDIRECT":{"ON_SUCCESS":"","ON_ERROR":""},"INPUTS":[],"INDEXFIELDS":[]},                                 
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