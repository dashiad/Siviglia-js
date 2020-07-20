Siviglia.Utils.buildClass({
    "context":"Siviglia.model.web.Page.forms",
    "classes":{
        Edit:{
            "inherits":"Siviglia.inputs.jqwidgets.Form",
            "methods":{
                preInitialize:function(params)
                {
                    var p={
                        "keys":params,
                        "model":"/model/web/Page",
                        "form":"Edit"
                    }
                    return this.Form$preInitialize(p);
                }
            }
        }
    }
});