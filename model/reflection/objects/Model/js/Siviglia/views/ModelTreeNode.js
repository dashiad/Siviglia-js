Siviglia.Utils.buildClass({
    context: "Siviglia.model.reflection.Model.views",
    classes: {
        "ModelTreeNode": {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
                preInitialize: function (params) {
                    this.svg=params.svg;
                    this.treeWidget=params.tree;
                    this.data = params.data;
                    this.name = Siviglia.empty(this.data.name)? this.data.item : this.data.name;
                    this._children=Siviglia.isset(params.data._children);
                    this.children=Siviglia.isset(params.data.children);
                    this.hasChildren=this._children || this.children;
                    this.selected=false;
                    if(this.hasChildren)
                    {
                        this.expanded=this.children;
                    }
                    this.bgcolor = "#333";
                    switch (this.data.resource) {
                        case "Package":{
                            this.bgcolor = "orange";
                            this.icon = "\uE9CC";
                        }break;
                        case "Model": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Action": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA3B";
                        }break;
                        case "Datasource": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA3D";
                        }break;
                        case "TYPE": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA27";
                        }break;
                        case "Type Metadata": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA27";
                        }break;
                        case "Definition": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Config": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Widget": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Worker": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Html Form": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Html Form Template": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA37";
                        }break;
                        case "Html View": {
                            this.bgcolor = "#333";
                            this.icon = "\uEA37";
                        }break;
                        case "Js Type": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Js Model": {
                            this.bgcolor = "\uEA38";
                            this.icon = "\uE9CC";
                        }break;
                        case "Js Form": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        case "Js View": {
                            this.bgcolor = "#333";
                            this.icon = "\uE9CC";
                        }break;
                        default:{
                            this.icon = '\uE9CC';
                        }
                    }


                },
                initialize: function (params) {
                    //if(this.expandIcon)
                    //    this.svg.on("click",function(){m.expand()});

                    if(this._children) {
                        this.flipAnimation(this.animationDown);
                    }
                },
                expand:function(node,params)
                {
                    if(!this.expanded) {
                        this.treeWidget.expandTree(this.data, 1);
                        this.expanded=true;


                    }
                    else {
                        this.expanded=false;

                        this.treeWidget.collapseTree(this.data);
                    }
                    this.flipAnimation(this.animationDown);
                },
                flipAnimation:function(anim)
                {
                    var temp=anim.attr("from");
                    anim.attr("from",anim.attr("to"));
                    anim.attr("to",temp);
                    anim[0].beginElement();
                },
                toggleSelection:function()
                {
                    if(this.selected)
                    {
                        this.treeWidget.unselect(this.data);
                        this.selected=false;
                    }
                    else {
                        this.treeWidget.select(this,this.data)
                        this.selected=true;
                    }
                },
                deselect:function()
                {
                    this.selected=false;
                }
            }
        }
    }
})