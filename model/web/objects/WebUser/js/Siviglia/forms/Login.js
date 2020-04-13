Siviglia.Utils.buildClass({
    "context":"Siviglia.model.web.WebUser.forms",
    "classes":{
        Login:{
            "inherits":"Siviglia.inputs.jqwidgets.Form",
            "methods":{
                preInitialize:function(params)
                {
                    var p={
                        "keys":params,
                        "model":"/model/web/WebUser",
                        "form":"Login"
                    }
                    return this.Form$preInitialize(p);
                }
            }
        }
    }
});
