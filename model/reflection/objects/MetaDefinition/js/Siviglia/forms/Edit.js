Siviglia.Utils.buildClass({
    "context":"Siviglia.model.reflection.MetaDefinition.forms",
    "classes":{
        Edit:{
            "inherits":"Siviglia.inputs.jqwidgets.Form",
            "methods":{
                preInitialize:function(params)
                {
                    var p={
                        "keys":params,
                        "model":"/model/reflection/MetaDefinition",
                        "form":"Edit"
                    };
                    return this.Form$preInitialize(p);
                }
            }
        }
    }
});