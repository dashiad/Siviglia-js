   define(["dojo/_base/declare","dojo/_base/lang","Siviglia/ModelInstance"],
          function(declare,lang,modelInstance)
          {
              return declare([modelInstance],
                             {
                                 constructor:function(model)
                                 {
                                     this.inherited(arguments);
                                 }
                             });
          }
   );