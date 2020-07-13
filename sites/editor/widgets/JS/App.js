Siviglia.Utils.buildClass({
    "context": "Siviglia.site.widgets.JS",
    classes: {
        "App": {
            "inherits": "Siviglia.UI.Expando.View",
            "methods": {
                /*
                    Params: iconId, iconTitle, iconImage,
                    windowTitle,windowWidth,windowHeight
                    targetWidget,targetWidgetParams
                 */
                preInitialize:function(params)
                {
                    nJDSK.iconHelper.addIcon(
                        params.iconId,
                        params.iconTitle,
                        params.iconImage,
                        this.openWindow.bind(this)
                        );
                    this.params=params;
                    this.window=null;

                },
                initialize:function(params)
                {

                },
                openWindow:function(e)
                {
                    e.preventDefault();
                    var newWindow = new nJDSK.Window(
                        Siviglia.issetOr(this.params.windowWidth,640),
                        Siviglia.issetOr(this.params.windowHeight,480),
                        Siviglia.issetOr(this.params.windowTitle,""),
                        '','', nJDSK.uniqid());
                    // add text to window footer (window footer is optional)
                    //newWindow.setFooter('This is a dynamic footer');
                    this.window=newWindow;
                    var contentNode=this.window.getContentNode();
                    // Se obtiene el widget a cargar.
                    var factory=new Siviglia.UI.Expando.WidgetFactory();
                    var m=this;
                    factory.get(this.params.targetWidget,this.context).then(function(i){
                        var target=Siviglia.Utils.stringToContextAndObject(m.params.targetWidget);
                        var f=target.context[target.object];
                        m.app=new f(
                            m.params.targetWidget,
                            m.params.targetWidgetParams,
                            {},
                            $(contentNode),
                            m.__context
                        );
                        m.app.__build();
                    });
                    this.window.onAfterClose=function(){
                        m.app.destruct();
                        m.destruct();
                    };
                    return false;
                }
            }
        }
    }
});
