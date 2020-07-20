Siviglia.Utils.buildClass({
    "context":"Siviglia.model.reflection.MetaDefinition.forms",
    "classes":{
        Add:{
            "inherits":"Siviglia.inputs.jqwidgets.Form",
            "methods":{
                preInitialize:function(params)
                {
                    var p={
                        "keys":params,
                        "model":"/model/reflection/MetaDefinition",
                        "form":"Add"
                    };
                    return this.Form$preInitialize(p);
                }
            }
        }
    }
});