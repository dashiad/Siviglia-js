Siviglia.Utils.buildClass({
    "context":"Siviglia.site.widgets.JS",
    classes:{
        "Desktop":{
            "inherits":"Siviglia.UI.Expando.View",
            "methods":{
                preInitialize:function(params){
                    top.nJDSKCurrentTheme="redmond";
                    nJDSK.init();
                    this.applications=[
                        {
                            iconId:'resIcon',
                            iconTitle:'Definition Editor',
                            iconImage:'http://statics.adtopy.com/packages/njdesktop/images/bws_logo2k9.png',
                            windowTitle:'Test App',
                            windowWidth:640,
                            windowHeight:480,
                            targetWidget:'Siviglia.model.reflection.MetaDefinition.forms.Add',
                            targetWidgetParams:{}
                        },
                        {
                            iconId:'resIcon2',
                            iconTitle:'Test App',
                            iconImage:'http://statics.adtopy.com/packages/njdesktop/images/bws_logo2k9.png',
                            windowTitle:'Model Explorer',
                            windowWidth:640,
                            windowHeight:480,
                            targetWidget:'Siviglia.model.reflection.Model.views.ModelEditor',
                            targetWidgetParams:{}
                        }
                    ];


                },
                initialize:function(params){
                    this.createMenus();
                    this.createApplications();
                    // load optional background image for the desktop environment
                    nJDSK.setBackground('http://statics.adtopy.com/packages/njdesktop/images/colorful-hq-background-1920x1200.jpg');
                },
                createMenus:function()
                {

                    /*addMenu params: parent, id, title, href, icon, function(optional)*/
                    nJDSK.menuHelper.addMenu('','linksmenu','Links','#','');
                    /*This menu item creates a dialog when clicked*/
                    nJDSK.menuHelper.addMenu('linksmenu','linksmenu-1','Link with icon and callback','#','http://statics.adtopy.com/packages/njdesktop/images/icons/silk/link.png',function(){
                        nJDSK.customHeaderDialog(
                                'Callback for Links &gt; Link with icon and callback',
                                'Callback',
                                'This dialog popped up after you clicked on that menu item',
                                [
                                    {
                                        type:'ok_yes',
                                        value:'OK',
                                        callback:function(win)
                                        {
                                            win.close();
                                        }
                                    },
                                    {
                                        type:'no_cancel',
                                        value:'Cancel',
                                        callback:function(win)
                                        {
                                            win.close();
                                        }
                                    }
                                ]
                            );
                            return false;
                        });
                        /*dummy menus to fill up the menu*/
                        nJDSK.menuHelper.addMenu('','othermenu','Other','#','');
                        nJDSK.menuHelper.addMenu('othermenu','othermenu-1','Other SubMenu item','#','');
                        nJDSK.menuHelper.addMenu('othermenu','othermenu-2','Other SubMenu item','#','');

                        // demo menus for nJDSK extra functions
                        nJDSK.menuHelper.addMenu('','windowmenu','Window','#','');
                        // tile menu
                        nJDSK.menuHelper.addMenu('windowmenu','tile-1','Tile','#','',function(){
                            nJDSK.tile();
                            return false;
                        });
                        // cascade menu
                        nJDSK.menuHelper.addMenu('windowmenu','cascade-1','Cascade','#','',function(){
                            nJDSK.cascade();
                            return false;
                        });

                },
                createApplications:function()
                {
                    this.appWidgets=[];
                    // Antes de crear, vamos a asegurarnos de que se carga.
                    var factory=new Siviglia.UI.Expando.WidgetFactory();
                    var m=this;
                    factory.get("Siviglia.site.widgets.JS.App",this.context).then(function(i){
                        var target=Siviglia.Utils.stringToContextAndObject("Siviglia.site.widgets.JS.App");

                        for(var k=0;k<m.applications.length;k++)
                        {
                            var d=$('<div></div>');
                            var curApp=m.applications[k];
                            var app=new target.context[target.object]("Siviglia.site.widgets.JS.App",
                                curApp,{},
                                d,
                                m.__context
                                );
                            app.__build();
                            m.appWidgets=app;
                        }
                    });
                }
            }
        }
    }
});

