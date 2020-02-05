Siviglia.Utils.buildClass(
    {
        context:'App.model.web.Page',
        classes:
            {
                Model:
                    {
                        inherits:'Siviglia.Model.Instance',
                        construct:function(model)
                        {
                            this.Instance(model);
                        }
                    }
            }
    });

var page=Adtopy.model.web.Page.Model();
var page=new Adtopy.model.web.Page.forms.Add();
var page=new Adtopy.model.web.Page.views.list();
page.getDomNode();

Model.getForm("/model/web/Page","Add").then(function(instance){});
