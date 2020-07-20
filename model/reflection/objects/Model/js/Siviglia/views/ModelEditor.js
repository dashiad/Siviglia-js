Siviglia.Utils.buildClass(
    {
        context:"Siviglia.model.reflection.Model.views",
        classes:{
            "ModelEditor":{
                inherits:"Siviglia.UI.Expando.View",
                destruct:function()
                {
                    if(this.modelView)
                        this.modelView.destruct();
                    if(this.currentItemView)
                        this.currentItemView.destruct();
                    this.lastSelectedItem=null;
                },
                methods:{
                    preInitialize:function(params)
                    {
                        this.modelView=null;
                        this.currentItemView=null;
                        this.editing=false;
                        this.shown="hidden";
                    },
                    initialize:function(params) {
                        this.refreshModelView();
                    },
                    refreshModelView:function() {
                        var ds = new Siviglia.Model.DataSource(
                            "/model/reflection/ReflectorFactory",
                            "FullTree",
                            {}
                        );
                        ds.refresh().then(function () {
                            if(this.modelView) {
                                this.modelView.removeListeners(this);
                                this.modelView.destruct();
                                this.svgView.html("");
                            }
                            var data = ds.data[0].root;
                            data.name="Adtopy";
                            var d=$("<div></div>")

                            this.modelView= new Siviglia.visual.Tree("Siviglia.visual.Tree",
                                {nodeWidget:"Siviglia.model.reflection.Model.views.ModelTreeNode",
                                    svgWidth:900,
                                    svgHeight:400,
                                    nodeWidth:100,
                                    nodeHeight:50,
                                    data:data,
                                    initialExpandedLevels:1,
                                    spacingWidth:25,
                                    spacingHeight:100
                                },
                                {}, d, this.__context);
                            this.modelView.__build().then(function() {
                                var m=this;
                                this.modelView.addListener("SELECTION_CHANGE",this,"onItemSelected");
                                this.modelView.addListener("SELECTION_EMPTY",this,"onSelectionEmpty");

                                this.svgView.append(this.modelView.rootNode);
                            }.bind(this));
                        }.bind(this))
                    },
                    onItemSelected:function(evName,params)
                    {
                        if(this.currentItemView)
                        {
                            this.currentItemView.destruct();
                            this.componentViewContainer.html("");
                        }
                        var item=params.selection[0].d;
                        this.lastItemSelected=params.selection[0];
                        this.editing=true;
                        this.shown="shown";
                        var resourceType=item.resource;
                        // Se prepara el nombre del widget de edicion.
                        // Si el nombre del recurso era "model", se carga Siviglia.Reflection.Model.

                        var targetWidgetName="Siviglia.model.reflection.Model.views."+(item.resource[0].toUpperCase()+item.resource.substr(1));
                        var widgetFactory=new Siviglia.UI.Expando.WidgetFactory();
                        var f=function(){
                            var target=Siviglia.Utils.stringToContextAndObject(targetWidgetName);
                            var targetW=target.context[target.object];
                            var d=$("<div></div>");
                            this.currentItemView=new targetW(targetWidgetName,{info :item},{},d,this.__context);
                            this.currentItemView.__build().then(function(){
                                this.componentViewContainer.append(this.currentItemView.rootNode);
                            }.bind(this));
                        }.bind(this);
                        if(widgetFactory.hasInstance(targetWidgetName))
                            f();
                        else
                            widgetFactory.getInstance(targetWidgetName,this.__context).then(f);
                    },
                    onSelectionEmpty:function()
                    {
                        if(this.currentItemView)
                        {
                            this.currentItemView.destruct();
                            this.componentViewContainer.html("");
                        }
                        this.editing=false;
                        this.shown="hidden";
                        this.modelView.unselect(this.lastItemSelected.d);
                        this.lastItemSelected=null;
                    },
                    closeComponentView:function()
                    {
                        this.onSelectionEmpty();
                    }
                }
            }
        }
    }
);