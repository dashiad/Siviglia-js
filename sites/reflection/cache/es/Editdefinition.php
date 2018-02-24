<?php
    $v0page=\Registry::getService("page");
?><?php
   $v1site=\Registry::getService("site");
   $v1ds=\lib\datasource\DataSourceFactory::getDataSource('\model\reflection\ReflectorFactory','NamespaceList');
   $v1it=$v1ds->fetchAll();
?><?php
    $v4page=\Registry::getService("page");
    $v4site=\Registry::getService("site");
    $v4name=$v4page->getPageName();
    $v4siteName=$v4site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v4site->getDefaultIso();?>">
<head>
    <title>Mi titulo</title><?php $__serialized__bundle__Site=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Site.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/font-awesome/css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/nprogress/nprogress.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/iCheck/skins/flat/green.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/jqvmap/dist/jqvmap.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/build/css/custom.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/jquery/dist/jquery.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/fastclick/lib/fastclick.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/iCheck/icheck.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/Siviglia.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/SivigliaTypes.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/Model.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/SivigliaStore.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/ModelInstance.js" ></script>
<script type="text/javascript">

                var Siviglia=Siviglia || {};
                Siviglia.config={
                    baseUrl:'<?php echo $v1site->getCanonicalUrl();?>/',
                    publicUrl:'<?php echo $v1site->getCanonicalUrl();?>',
                    namespaces:['backoffice','web'],
                    defaultNamespace:'backoffice',
                    jsFramework:'jquery',
                    datasourcePrefix:'datasources',
                    isDevelopment:0,
                    mapper: 'BackofficeMapper',
                    id_lang:'es'
                };
               Siviglia.Model.initialize(Siviglia.config);
                var oApp=new Siviglia.App.App({});
                var Page=new Siviglia.App.Page(oApp);
                oApp.setPage(Page);
            </script>
<script type="text/javascript">

                Siviglia.Utils.buildClass(
                    {
                        context: 'Reflection',
                        classes:
                            {
                                Paths:{
                                    construct:function()
                                    {
                                        this.paths={
                                            "loadDefinition":"/Reflection/Definitions/[%name%]",
                                            "definitionList":"/Reflection/"
                                        }
                                    },
                                    methods:
                                        {
                                            buildPath:function(name,params,controller)
                                            {
                                                var entry=Siviglia.issetOr(this.paths[name],null);
                                                if(entry==null)
                                                    throw "Path desconocido : "+name;
                                                var baseUrl=null;
                                                var link=null;
                                                if(Siviglia.isObject(entry))
                                                {
                                                    baseUrl=entry.baseUrl;
                                                    link=entry.link;
                                                }
                                                else
                                                {
                                                    baseUrl=top.Siviglia.config.publicUrl;
                                                    link=entry;
                                                }
                                                var ps=new Siviglia.Utils.ParametrizableString(controller);
                                                return ss.parse(baseUrl+link,params);
                                            }
                                        }
                                }
                            }
                    });
                top.Siviglia.Paths=new Reflection.Paths();
            </script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.js" ></script>
<?php $__serialized__bundle__Page=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Page.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//packages/ionicons/css/ionicons.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//packages/jqwidgets/styles/jqx.base.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//packages/jqwidgets/styles/jqx.flat.css"/>
<style type="text/css">

    .remove, .NewItemString .add {
        font-family:'IcoMoon'
    }
    .remove:before {

    }
    .description:empty {display:none}
    .NewItemString .add {
        margin-left: 5px;
        margin-top: 6px;
        color: green;
    }
    .add:before {
        font-family:"Ionicons";
        content: '\f359';
        font-size: 20px;
    }
    .remove {
        font-family: 'IcoMoon';
    }
    input[type=checkbox]{
        width:15px !important;
        height:15px !important;
    }
    .ContainerType {}
    .containerLabel {
        min-width: 200px;
        display: table-cell;
        vertical-align: top;
        text-align: right;
        padding-right: 7px;
    }
    .ContainerType .ContainerType .containerLabel {
        min-width:150px !important;
        font-size:12px
    }
    .containerInput {
        display: table-cell;
    }
    .dictionaryInput {
        min-width:200px
    }
    .removebutton:after {
        font-family:"Ionicons";
        font-size: 16px;
        content: "\f128";
        color: darkred;
        margin-top: 7px;
        margin-left: 5px;
    }
    .navbar-form {
        padding:0px !important;
    }
</style>
<style type="text/css">

            .miclase {width:100px;font-weight:bold}
            </style>
<style type="text/css">

            .miclase {width:100px;font-weight:bold}
            </style>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/AutoUI.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/jqwidgets/jqx-all.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/jQuery/forms/inputs/BaseInput.js" ></script>
<script type="text/javascript">

    Siviglia.Utils.buildClass({
    context:'Siviglia.AutoUI.Painter',
    classes:{
        Factory:{
            inherits:"Siviglia.UI.Widget,Siviglia.Dom.EventManager",
            methods:{
                preInitialize:function(params){
                    this.parentObject=params.parentObject;
                    this.parentNode=params.parentNode;
                    this.controller=params.controller;
                    this.value=params.value;
                },
                initialize:function(params){
                    this.container=$(".factoryContainer",this.rootNode);
                    var rootWidget=this.factory(params.parentNode,null,{});
                    this.container.append(rootWidget.view.rootNode);
                },
                factory:function(nodeObj,parentNode,args)
                {
                    args = args || {};
                    args.uinode = nodeObj;
                    args.parentNode = parentNode;
                    args.controller= this.controller;
                    args.painterFactory=this;
                    node = args;
                    var dv=$('<div></div>');

                    if (nodeObj.definition.PAINTER) {
                        return new
                        Siviglia.AutoUI.Painter[nodeObj.definition.PAINTER](
                            'AUTOPAINTER_'+nodeObj.definition.PAINTER,
                            args,{},dv,Siviglia.model.Root
                        );
                    }

                    var type=nodeObj.getClassName();

                    var equivs={
                        "IntegerType":"StringPainter",
                        "StringType":"StringPainter",
                        "BooleanType":"BooleanPainter",
                        "ContainerType":"ContainerPainter",
                        "DictionaryType":"DictionaryPainter",
                        "ArrayType":"ArrayPainter",
                        "KeyReferenceType":"KeyReferencePainter",
                        "SelectorType":"SelectorPainter",
                        "TypeSwitcher":"TypeSwitchPainter",
                        "ObjectArrayType":"ObjectArrayPainter",
                        "SivObjectSelector":"SelectorPainter",
                        "SubdefinitionType":"SubdefinitionPainter",
                        "FixedDictionaryType":"FixedDictionaryPainter"
                    };
                    if(!equivs[type])
                    {
                        // Si no se encuentra un tipo de painter asociado, se mira si existe una clase del proyecto,
                        // que nos diga que painter usar.
                        // Es decir, que un tipo de dato "custom" debe proveer de una clase en el namespace Siviglia.AutoUI,
                        // que gestiona el tipo, y que tiene una variable PAINTER "estatica" (prototipo)
                        if(Siviglia.isset(Siviglia.AutoUI[type]))
                            equivs[type]=Siviglia.AutoUI[type].PAINTER;

                        throw "NO ENCONTRADO WIDGET PARA "+type;
                    }
                    if(!Siviglia.AutoUI.Painter[equivs[type]])
                    {
                        throw "NO ENCONTRADO PAINTER PARA "+type+" : ("+equivs[type]+")";
                    }


                    var w=new Siviglia.AutoUI.Painter[equivs[type]]('AUTOPAINTER_'+type,
                        args,
                        {},
                        dv,
                        Siviglia.model.Root
                    );
                    return w;
                },
                // Funcion a sobreescribir para crear nuevos layouts.
                getLayout:function(params)
                {
                    var cName = params.uinode.getClassName();
                    var defaultLayouts={
                        "StringType": "SymmetricalLayout",
                        "ContainerType": "SymmetricalLayout",
                        "ArrayType": "SymmetricalLayout",
                        "SelectorType": "SymmetricalLayout",
                        "DictionaryType": "LateralMenuLayout",
                        "TypeSwitcher": "SymmetricalLayout",
                        "ObjectArrayType": "LateralMenuLayout",
                        "SubdefinitionType":"WindowLayout"
                    };
                    //var layout=defaultLayouts[cName];
                    //var obj=Siviglia.Utils.stringToContextAndObject(layout);
                    //return new obj.context[obj.object](params)
                    return defaultLayouts[cName];
                }
            }
        },
        BasePainter:
        {
            inherits:"Siviglia.UI.Widget,Siviglia.Dom.EventManager",
            methods:{
                preInitialize:function(params)
                {
                    this.uinode=params.uinode;
                    this.parentNode=params.parentNode;
                    this.controller=params.controller;
                    this.painterFactory=params.painterFactory;
                    this.title=this.uinode.definition.LABEL || '';
                    this.description=this.uinode.definition.DESCRIPTION || '';
                    this.helpText=Siviglia.issetOr(this.uinode.definition.HELP || '');
                    var m=this;
                    this.uinode.addListener("change",this,"reload");
                },
                reload:function(event)
                {
                    this.notifyPathListeners();
                }

            }
        },
        SelectorPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);
                    var h= $.Deferred();
                    var m=this;
                    params.uinode.loadSource().then(function(v){
                        m.options=v;
                        h.resolve();
                    })
                    return h;
                },
                initialize:function(params)
                {

                    this.inputF=new Siviglia.Forms.JQuery.Inputs.Enum(null,this.inputNode);
                    var v=params.uinode.definition;

                    this.inputF.sivInitialize(v,params.uinode.getValue(),{});
                    var m=this;
                    this.inputF.on("change",function(node){
                        if(m.syntheticChange)
                            return;
                        m.syntheticChange=true;
                        m.uinode.setValue(m.inputF.getValue());
                        m.syntheticChange=false;

                    })
                },
                getValue: function () {
                    return this.combo.val();
                }
            }
        },

        DictionaryPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);

                    this.currentWidget=null;
                    this.currentKey=null;
                    // Miramos si el tipo que vamos a generar, es simple o no.
                    var childType=params.uinode.getValueInstance(null,null);
                    this.hasSimpleType=childType.isSimpleType();
                },
                initialize:function(params)
                {
                    if(!this.uinode.definition.SAVE_URL)
                        this.saveNode.css({"display":"none"})
                },
                buildNewItemWidget:function(node,params)
                {
                    if(this.newItemWidget) {
                        this.newItemWidget.destruct();
                    }
                    node.html("");

                    var pp=this.params;
                    pp.parent=this;
                    var tempNode=$("<div></div>");
                    this.newItemWidget=new Siviglia.AutoUI.Painter.NewItemPainter('AUTOPAINTER_NewItem',
                        pp,
                        {},
                        tempNode,
                        Siviglia.model.Root
                    );
                    node.append(tempNode);
                },
                doSave:function()
                {
                    if(this.uinode.definition.SAVE_URL)
                    {
                        this.uinode.saveToUrl();
                    }
                },
                onLabelClicked:function(node, params)
                {
                    console.log("*****LABEL CLICKED*****");
                    this.currentKey=params.key;
                    this.notifyPathListeners();

                },
                getInputFor:function(node,params)
                {
                    if(this.currentWidget!=null) {
                        this.currentWidget.destruct();
                        this.currentWidget = null;
                        node.html("");
                    }
                    if(params.key==null)
                        return;
                    var newNode=this.__getInputFor(params.key);
                    node.append(newNode);
                },
                __getInputFor:function(key)
                {
                    var newWidget=this.painterFactory.factory(this.uinode.children[key], this.uinode.parent,{});
                    //this.currentWidget=newWidget;
                    //this.currentKey=key;
                    return newWidget.rootNode;
                },
                getLabel:function(node,params,event)
                {
                    var curKey=params.key;
                    node.html(this.uinode.definition.FIELDS[curKey].LABEL || curKey);
                },
                onRemoveClicked:function(node,params,event)
                {
                    if(params.key==this.currentKey && this.currentWidget!=null)
                    {
                        this.currentWidget.destruct();
                    }
                    this.currentKey=null;
                    this.currentWidget=null;
                    this.uinode.removeItem(params.key);
                },
                addItem:function(val)
                {
                    this.uinode.addItem(val);
                    this.onLabelClicked(null,{key:val});
                },
                reload:function(event)
                {

                    this.BasePainter$reload(event);
                }

            }
        },
        FixedDictionaryPainter:
            {
              inherits:'DictionaryPainter',
                methods:
                    {
                        preInitialize:function(params)
                        {
                            this.BasePainter$preInitialize(params);
                            this.currentWidget=null;
                            this.currentKey=null;
                        },

                        buildNewItemWidget:function(node,params)
                        {
                            if(this.newItemSelector)
                            {
                                this.newItemSelector.node.remove();
                                this.newItemSelector=null;
                            }
                            var uinode=this.params.uinode;
                            var possibleKeys=this.params.uinode.getPossibleKeys();
                            // Se hace una copia de las keys posibles.
                            var target={};
                            for(var k in possibleKeys)
                            {
                                target[k]=possibleKeys[k];
                            }
                            // Si el nodo tiene un valor, eliminamos las keys ya existentes, para que solo se puedan
                            // crear keys aun no usadas.
                            if(!uinode.isUnset())
                            {
                                var v=uinode.getValue();
                                for(var k in v)
                                    delete target[k];
                            }
                            var opts=[];
                            for(var j in target)
                            {
                                opts.push(target[j].LABEL);
                            }
                            if(opts.length>0) {

                                this.newItemSelector = new Siviglia.Forms.JQuery.Inputs.Enum(null, this.newItemNode);
                                this.newItemSelector.sivInitialize({TYPE: 'Enum', VALUES: opts}, null, {});

                                var m = this;
                                this.newItemSelector.on("change", function (ev) {
                                    if (m.syntheticChange)
                                        return;
                                    m.syntheticChange = true;
                                    var curLabel = m.newItemSelector.getValue();
                                    var tt = m.uinode.getPossibleKeys();
                                    for (var k in tt) {
                                        if (tt[k].LABEL == curLabel) {
                                            m.onChangeType(k);
                                            m.syntheticChange = false;
                                            m.buildNewItemWidget(node,{});
                                        }
                                    }
                                });
                                node.append(this.newItemSelector.node);
                            }

                        },
                        onChangeType:function(val)
                        {
                            var uinode=this.params.uinode;
                            var possibleKeys=this.params.uinode.getPossibleKeys();
                            var type=possibleKeys[val]["TYPE"];
                            this.uinode.addItem(val);
                            this.onLabelClicked(null,{key:val});

                        },
                        onRemoveClicked:function(node,params,event)
                        {
                            this.DictionaryPainter$onRemoveClicked(node,params,event);
                            this.buildNewItemWidget(this.newItemNode);
                        },
                        getInputFor:function(node,params)
                        {
                            if(this.currentWidget!=null) {
                                this.currentWidget.destruct();
                                this.currentWidget = null;
                                node.html("");
                            }
                            if(params.key==null)
                                return;
                            var newNode=this.__getInputFor(params.key);
                            node.append(newNode);
                        },
                        __getInputFor:function(key)
                        {
                            var newWidget=this.painterFactory.factory(this.uinode.children[key], this.uinode.parent,{});
                            this.currentWidget=newWidget;
                            this.currentKey=key;
                            return newWidget.rootNode;
                        },
                    }
            },
        ContainerPainter:
        {
            inherits:'DictionaryPainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);

                    this.currentWidget=null;
                    this.currentKey=null;
                },
                initialize:function(params)
                {
                    this.DictionaryPainter$initialize(params);
                    if(!this.uinode.definition.SAVE_URL)
                    {
                        this.saveNode.css({"display":"none"})
                    }
                },
                getSubInput:function(node,params)
                {
                    console.log("************GETTING SUBINPUT*****");
                    var value=this.uinode.children[params.key];
                    var currentWidget=this.painterFactory.factory(value,this.uinode.parent, {});
                    node.append(currentWidget.rootNode);
                },
                doSave:function()
                {
                    if(this.uinode.definition.SAVE_URL)
                    {
                        this.uinode.saveToUrl();
                    }
                }
            }
        },
        StringPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);
                    this.value=params.uinode.getValue();
                },
                initialize:function(params)
                {
                    this.inputF=new Siviglia.Forms.JQuery.Inputs.String(null,this.inputNode);
                    this.inputF.sivInitialize(params.uinode.definition,params.uinode.getValue(),{});
                    var m=this;
                    this.inputF.on("change",function(node){
                        m.uinode.setValue(m.inputF.getValue());
                    })
                }
            }
        },
        BooleanPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);
                    this.checked=(params.uinode.getValue()===true)?"checked":"";
                },
                initialize:function(params)
                {

                    this.inputF=new Siviglia.Forms.JQuery.Inputs.Boolean(null,this.inputNode);
                    this.inputF.sivInitialize(params.uinode.definition,params.uinode.getValue(),{});
                    var m=this;
                    this.inputF.on("change",function(node){
                        m.uinode.setValue(m.inputF.getValue()=="1"?true:false);
                    })

                }
            }

        },
        ObjectArrayPainter: {
            inherits:'BasePainter',
            methods: {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);
                    this.currentWidget=null;
                    this.currentKey=null;
                    this.uinode=params.uinode;
                    this.keyDirection="HORIZONTAL";
                },
                initialize:function(params)
                {

                    this.nElems=this.uinode.children.length;
                    this.widgetContainer=$('.currentWidget',this.rootNode);
                    params.parent=this;
                    //if(!this.uinode.definition.SAVE_URL)
                    //    this.saveNode.css({"display":"none"})
                },
                doSave:function()
                {
                    if(this.uinode.definition.SAVE_URL)
                    {
                        this.uinode.saveToUrl();
                    }
                },
                onLabelClicked:function(node, params)
                {
                        if(this.currentWidget)
                            this.currentWidget.destruct();
                        this.currentKey=params.index;
                        this.currentWidget=this.painterFactory.factory(this.uinode.children[params.index], this.uinode.parent,{});
                        this.widgetContainer.append(this.currentWidget.rootNode);
                },
                getLabel:function(node,params,event)
                {
                    if(typeof this.uinode.definition.VALUELABEL!="undefined")
                        node.html(this.uinode.getValue()[params.index][this.uinode.definition.VALUELABEL]);
                    else
                        node.html(params.index);
                },
                onRemoveClicked:function(node,params,event)
                {
                        this.uinode.removeItem(params.index);
                        if(params.index==this.currentKey)
                        {
                            this.currentWidget.destruct();
                        }
                },
                addItem:function(node)
                {
                    var val=this.uinode.addItem(null);
                    this.onLabelClicked(null,{index:this.uinode.children.length-1});
                },
                reload:function(event)
                {
                    this.nElems=this.uinode.children.length;
                    this.BasePainter$reload(event);
                }

            }
        },
        FixedPainter: {
            inherits:'BasePainter',
            methods:
                {
                    preInitialize:function(params)
                    {
                        var tt=params;
                        this.uinode=params.uinode;
                    }
                }
        },
        ArrayPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.BasePainter$preInitialize(params);
                    this.values=params.uinode.getValue()
                },
                initialize:function(params)
                {
                    this.newItemWidget=new Siviglia.AutoUI.Painter.NewItemPainter('AUTOPAINTER_NewItem',
                        params,
                        {},
                        this.newItemNode,
                        Siviglia.model.Root
                    );
                },
                add:function(node)
                {
                    var val=this.newElementInput.val();
                    this.uinode.addItem(val);
                },
                onRemoveClicked:function(node,params)
                {
                    this.uinode.remove(params.key);
                }
            }
        },
        SubdefinitionPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    // No podemos pintarnos hasta que se cargue la definicion.
                    this.BasePainter$preInitialize(params);
                    return params.uinode.getEntityPromise();
                },
                initialize:function(params)
                {
                    // Se crea un sub-parser, a partir del nodo recibido
                    var v=$("<div></div>");
                    var s=new Siviglia.AutoUI.Painter.Factory('AUTOUI_FACTORY',
                        {parentObject:null,parentNode:params.uinode.subController.rootNode,controller:this.controller,painterFactory:this.painterFactory},
                        {},
                        v,
                        Siviglia.model.Root);
                    this.subcontainer.append(v);
                }
            }
        },
        TypeSwitchPainter:
            {
                inherits:'BasePainter',
                methods:
                    {
                        preInitialize:function(params)
                        {
                            this.BasePainter$preInitialize(params);
                            this.value=params.uinode.getValue()
                            this.typeNode = null;
                            this.typeSelector=null;
                        },
                        initialize:function(params)
                        {
                            this.params=params;
                            this.paintValue();
                        },
                        paintValue: function () {
                            if (this.typeNode) {
                                this.typeNodeWidget.destroy();
                                this.typeNode.destruct();
                            }
                            this.createTypeSelector();


                            var val;
                            if (this.params.uinode.isUnset())
                                val = null;
                            else
                                val = this.params.uinode.getCurrentType();

                            if(val!=null) {
                                this.typeNode = Siviglia.AutoUI.NodeFactory(this.params.uinode.getSubNode().definition, null, val, this.uinode.controller);
                            }
                            else
                                this.typeNode=null;

                            this.repaintType();

                        },
                        repaintType:function()
                        {
                            if(this.subNodeWidget)
                                this.subNodeWidget.destruct();
                            this.fieldContainer.innerHTML='';
                            if (!this.params.uinode.isUnset()) {
                                var sNode = this.params.uinode.getSubNode();
                                this.subNodeWidget = this.painterFactory.factory(sNode, this.params.uinode.parent,{});
                                this.fieldContainer.append(this.subNodeWidget.rootNode);
                            }
                        },
                        createTypeSelector:function()
                        {
                            if(this.typeSelector!=null)
                                return;
                            //var vals = this.params.uinode.definition.ALLOWED_TYPES;
                            var vals=this.params.uinode.getAllowedTypes();
                            var val;
                            if (this.params.uinode.isUnset())
                                val = null;
                            else
                                val = this.params.uinode.getCurrentType();

                            this.inputF=new Siviglia.Forms.JQuery.Inputs.Enum(null,this.typeSwitchSelector);
                            this.inputF.sivInitialize({TYPE:'Enum',VALUES:vals},val,{});

                            var m=this;
                            this.inputF.on("change",function(node){
                                if(m.syntheticChange)
                                    return;
                                m.syntheticChange=true;
                                m.onChangeType(m.inputF.getValue());
                                m.syntheticChange=false;

                            })
                        },
                        onChangeType: function (v) {


                            if (!v) {
                                alert("Please choose a type");
                                return;
                            }
                            if (this.params.uinode.getCurrentType() == v) {
                                return;
                            }
                            this.params.uinode.setType(v);
                            this.repaintType();
                        }
                    }
            },
        NewItemPainter:
        {
            inherits:'BasePainter',
            methods:
            {
                preInitialize:function(params)
                {
                    this.params=params;
                    this.BasePainter$preInitialize(params);
                },
                initialize:function()
                {
                    this.uinode.addListener("change",this,"paintInput");
                    this.paintInput();
                },
                paintInput:function()
                {
                    if (this.uinode.hasSourceInput()) {
                        this.mode=0;
                        this.newItemString.css({display:'none'});
                        this.newItemSelector.css({display:'block'});
                        var m=this;
                        $.when(this.uinode.getSourceValues()).then(function(vals){
                            m.newItemSelector.html("");
                            var defaultOpt=$('<option value="" selected>--Elegir</option>');
                            m.newItemSelector.append(defaultOpt);
                            for(var k=0;k<vals.length;k++)
                            {
                                m.newItemSelector.append(
                                    $('<option value="'+vals[k].value+'">'+vals[k].name+'</option>')
                                );
                            }
                            m.syntheticChange=false;
                        })
                    }
                    else {
                        this.mode=1;
                        this.newItemString.css({display:'block'});
                        this.newItemSelector.css({display:'none'});
                    }
                },
                paintValue: function () {

                },
                onAdd: function () {
                    var val;
                    if(this.mode==1)
                        val=this.newItemString.val();
                    else
                        val=this.newItemSelector.val();
                    if (val == "") return;
                    this.params.uinode.addItem(val);
                    this.paintInput();
                }
            }
        }
    }
});

</script>
<script type="text/javascript">

                Siviglia.Utils.buildClass({
                    context: 'Components',
                    classes: {
                        Tabbar: {
                            inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods: {
                                constants:
                                    {
                                        TAB_CHANGED:1
                                    },
                                preInitialize:function(params)
                                {

                                    this.tabSource=params.tabSource;
                                    this.tabLabel=params.tabLabel;
                                    this.type=params.type;
                                    this.pathRoot.context.tabLabel=this.tabLabel;
                                    this.tabNodes={};
                                    this.curSelection=null;

                                },
                                getCurrentTab:function()
                                {
                                    return this.curSelection;
                                },
                                setCurrentTab:function(node,params)
                                {
                                    this.curSelection=params.current[this.tabLabel];
                                    this.fireEvent(Components.Tabbar.TAB_CHANGED,{node:node,value:this.curSelection,index:params.idx});
                                },
                                getTabLabel:function(node,params)
                                {
                                    node.html(params.current[this.tabLabel]);
                                    var val=params.current[this.tabLabel];
                                    node.attr("href","#tab-"+val);
                                    node.attr("id","tabLabel-"+val)
                                    if(params.index==0)
                                        node.parent().attr("class",node.attr("class")+" active");

                                },
                                getTabContent:function(node,params)
                                {
                                    var val=params.current[this.tabLabel];
                                    node.attr("id","tab-"+val);
                                    node.attr("aria-labelledby","tabLabel-"+val);
                                    this.tabNodes[params.current[this.tabLabel]]=node;
                                    if(params.index==0)
                                        this.curSelection=val;
                                }
                            }
                        },
                        SelectorOrNew:{
                            inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            preInitialize:function(params)
                            {
                                this.selectorSource=params.url;
                                this.options=params.options;
                                this.selectorLabel=params.label;
                                this.selectorValue=params.value;

                            },
                            initialize: function (params) {
                                var source =
                                    {
                                        datatype: "json",
                                        /*datafields: [
                                            { name: this.selectorLabel },
                                        ],*/
                                        url: this.selectorSource
                                    };
                                var dataAdapter = new $.jqx.dataAdapter(source);
                                this.siteCombo.jqxComboBox(
                                    {
                                        width: 200,
                                        height: 25,
                                        source: dataAdapter,
                                        displayMember: this.selectorLabel,
                                        valueMember: this.selectorValue
                                    });
                                var m=this;
                                this.siteCombo.on('change', function (event) {
                                    if (event.args) {
                                        var item = event.args.item;
                                        if (item) {
                                            m.fireEvent("ELEMENT_CHANGE",{site:item.value})
                                        }
                                    }
                                });
                            },
                            onNew:function()
                            {
                                var val=this.newItem.val();
                                if(val=="")
                                {
                                    alert("Introduce un nombre de configuracion");
                                    return;
                                }
                                this.fireEvent("NEW_ELEMENT",{site:val});
                            }
                        }
                    }
                });
            </script>
<script type="text/javascript">

    Siviglia.Utils.buildClass(
    {
        context:'Reflection.Widgets',
        classes:{
            DefinitionEditor:{
                inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                methods:{
                    preInitialize:function(params){

                        this.self=this;
                    },
                    initialize:function(params){
                        var m=this;
                        this.currentEditor=null;
                    },
                    editDefinition:function(name)
                    {
                        this.currentDefinition=name;
                        if(this.currentEditor!=null)
                            this.currentEditor.destruct();
                        var m=this;
                        $.ajax({
                            url:top.Siviglia.Paths("loadDefinition",{name:name}),
                            dataType:'json',
                            success:function(result)
                            {
                                m.createEditor(result)
                            },
                            error:function()
                            {
                                console.dir(arguments);
                            }
                        });
                    },
                    onNewSite:function(name)
                    {
                        this.currentDefinition=name;
                        if(this.currentEditor!=null)
                            this.currentEditor.destruct();
                        this.createEditor({
                            configType:"default",
                            actions:[
                                {
                                    regex:[".*"],
                                    actions:[]
                                }
                            ]
                        });
                    },
                    createEditor:function(value)
                    {
                        var nn=$("<div></div>");
                        this.currentEditor=new Reflection.Widgets.DefinitionForm(
                            "Reflection.Widgets.DefinitionForm",
                            {value:value},{},nn, Siviglia.model.Root
                        );
                        this.form.append(nn);
                    },
                    save:function()
                    {
                        $.post(
                            top.Siviglia.Paths("saveDefinition",{}),
                            {
                                name:name,
                                action:'save',
                                data:JSON.stringify(this.currentEditor.dump())
                            },
                            function(){
                                alert("Guardado");
                            }
                        );
                    }
                }
            }

        }
    }
);
Siviglia.Utils.buildClass(
    {
        context:'Reflection.Widgets',
        classes:
            {

                /*SiteSelector: {
                    inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                    methods: {
                        preInitialize: function (params) {

                            this.controller=params.controller;
                        },
                        initialize: function (params) {
                            var source =
                                {
                                    datatype: "json",
                                    datafields: [
                                        { name: 'name' },
                                    ],
                                    url: top.Config.baseUrl+"/smartclipConfig/datasources/currentConfigs"
                                };
                            var dataAdapter = new $.jqx.dataAdapter(source);
                            this.siteCombo.jqxComboBox(
                                {
                                    width: 200,
                                    height: 25,
                                    source: dataAdapter,
                                    displayMember: "name",
                                    valueMember: "name"
                                });
                            var m=this;
                            this.siteCombo.on('change', function (event) {
                                if (event.args) {
                                    var item = event.args.item;
                                    if (item) {
                                        m.fireEvent("SITE_CHANGE",{site:item.value})
                                    }
                                }
                            });
                        },
                        onNew:function()
                        {
                            var val=this.newConfig.val();
                            if(val=="")
                            {
                                alert("Introduce un nombre de configuracion");
                                return;
                            }
                            this.fireEvent("NEW_SITE",{site:val});
                        },
                        dump:function()
                        {
                            this.controller.dump();
                        }
                    }
                },*/

                DefinitionForm:{
                    inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                    methods:
                        {
                            preInitialize:function(params)
                            {
                                var m=new Siviglia.Model.Model("/model/reflection/ArrayDefinition");
                                var self=this;
                                debugger;
                                m.getDataSource("ListAll",{}).then(function(d){
                                    self.namespaces=d;
                                    p.resolve();});

                                this.value=params.value;
                            },
                            initialize:function(params)
                            {
                                var m=this;
                                var spec={"success":true,"data":{meta:
                                    {
                                        "ROOT": {
                                            "TYPE": "DICTIONARY",
                                            "LABEL": "Definition",
                                            "VALUETYPE": "NODE"
                                        },
                                        "NODE": {
                                            "TYPE": "TYPESWITCH",
                                            "TYPE_FIELD": "TYPE",
                                            "LABEL": "Node",
                                            "ALLOWED_TYPES": [
                                                "*INTEGER",
                                                "*STRING",
                                                "*BOOLEAN",
                                                "*CONTAINER",
                                                "*DICTIONARY",
                                                "*ARRAY",
                                                "*SELECTOR",
                                                "*TYPESWITCH",
                                                "*OBJECTARRAY"
                                            ]
                                        },
                                        "INTEGER": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Integer",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "DEFAULT": {
                                                    "LABEL": "Default Value",
                                                    "TYPE": "STRING",
                                                    "HELP": "Default value for field"
                                                }
                                            }
                                        },
                                        "STRING": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "String",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null or ''"
                                                },
                                                "DEFAULT": {
                                                    "LABEL": "Default Value",
                                                    "TYPE": "STRING",
                                                    "HELP": "Default value for field"
                                                }
                                            }
                                        },
                                        "BOOLEAN": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Boolean",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "DEFAULT": {
                                                    "LABEL": "Default Value",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "Default value for field"
                                                }
                                            }
                                        },
                                        "CONTAINER": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Container",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "LOAD_URL": {
                                                    "LABEL": "Load URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If defined, this element will fill itself with values received from this datasource in JSON format"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null or empty"
                                                },
                                                "FIELDS": {
                                                    "LABEL": "Fields",
                                                    "TYPE": "DICTIONARY",
                                                    "HELP": "Container Fields",
                                                    "VALUETYPE": "NODE"
                                                }
                                            }
                                        },
                                        "DICTIONARY": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Dictionary",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "LOAD_URL": {
                                                    "LABEL": "Load URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If defined, this element will fill itself with values received from this datasource in JSON format"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "SOURCE": {
                                                    "LABEL": "Source",
                                                    "TYPE": "STRING",
                                                    "HELP": "If set, this defines the source for the allowed keys in this dictionary"
                                                },
                                                "FIELDS": {
                                                    "LABEL": "Fields",
                                                    "TYPE": "DICTIONARY",
                                                    "HELP": "Container Fields",
                                                    "VALUETYPE": "NODE"
                                                }
                                            }
                                        },
                                        "ARRAY": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Array",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": "y"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "LOAD_URL": {
                                                    "LABEL": "Load URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If defined, this element will fill itself with values received from this datasource in JSON format"
                                                },
                                                "SAVE_URL": {
                                                    "LABEL": "Save URL",
                                                    "TYPE": "STRING",
                                                    "HELP": "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SOURCE": {
                                                    "LABEL": "Source",
                                                    "TYPE": "STRING",
                                                    "HELP": "If set, this defines the source for the allowed values in this array"
                                                }
                                            }
                                        },
                                        "SELECTOR": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Selector",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "OPTIONS": {
                                                    "LABEL": "Options",
                                                    "REQUIRED": true,
                                                    "TYPE": "OBJECTARRAY",
                                                    "VALUETYPE": "*OPTION"
                                                }
                                            }
                                        },
                                        "TYPESWITCH": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Type Switch",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "TYPE_FIELD": {
                                                    "LABEL": "Type field",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "ALLOWED_TYPES": {
                                                    "LABEL": "Allowed Types",
                                                    "TYPE": "OBJECTARRAY",
                                                    "REQUIRED": true,
                                                    "VALUETYPE": {
                                                        "TYPE": "*ALLOW_TYPE"
                                                    }
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                }
                                            }
                                        },
                                        "OBJECTARRAY": {
                                            "TYPE": "CONTAINER",
                                            "LABEL": "Object Array",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "VALUETYPE": {
                                                    "LABEL": "Object type",
                                                    "TYPE": "*OBJARRAY_VALUE"
                                                },
                                                "HANDLER": {
                                                    "LABEL": "Class Handler",
                                                    "TYPE": "STRING",
                                                    "HELP": "Instances of this class (in format a.b.c) will be created to handle this input events"
                                                },
                                                "REQUIRED": {
                                                    "LABEL": "Required?",
                                                    "TYPE": "BOOLEAN"
                                                },
                                                "DESCRIPTION": {
                                                    "LABEL": "Description",
                                                    "TYPE": "STRING",
                                                    "HELP": "Description to accompany this field"
                                                },
                                                "SET_ON_EMPTY": {
                                                    "LABEL": "Set on empty",
                                                    "TYPE": "BOOLEAN",
                                                    "HELP": "If true, this element will be saved if it's null"
                                                },
                                                "HELP": {
                                                    "LABEL": "Help",
                                                    "TYPE": "STRING",
                                                    "HELP": "Field Help"
                                                }
                                            }
                                        },
                                        "OPTION": {
                                            "TYPE": "DICTIONARY",
                                            "FIELDS": {
                                                "LABEL": {
                                                    "LABEL": "Label",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "VALUE": {
                                                    "LABEL": "Value",
                                                    "TYPE": "STRING",
                                                    "REQUIRED": true
                                                },
                                                "DEFAULT": {
                                                    "LABEL": "Is default",
                                                    "TYPE": "BOOLEAN"
                                                }
                                            }
                                        },
                                        "ALLOW_TYPE": {
                                            "TYPE": "CONTAINER",
                                            "FIELDS": {
                                                "TYPE": {
                                                    "TYPE": "SELECTOR",
                                                    "LABEL": "Type",
                                                    "SOURCE": "/",
                                                    "REQUIRED": true
                                                },
                                                "LABEL": {
                                                    "TYPE": "STRING",
                                                    "LABEL": "Label",
                                                    "REQUIRED": true
                                                }
                                            }
                                        },
                                        "OBJARRAY_VALUE": {
                                            "TYPE": "SELECTOR",
                                            "LABEL": "Type",
                                            "SOURCE": "/",
                                            "REQUIRED": true
                                        }
                                    }
                                    ,value:this.value}};
                                this.controller=new Siviglia.AutoUI.AutoUIController(top.page.App.config.baseUrl);
                                this.controller.initialize(spec.data.meta,spec.data.value);
                                this.doInitialize(spec.data,this.autoUINode);
                            },
                            doInitialize:function(data,node)
                            {

                                var v=$("<div></div>");
                                var B=new Siviglia.AutoUI.Painter.Factory('AUTOUI_FACTORY',
                                    {parentObject:null,parentNode:this.controller.rootNode,controller:this.controller},
                                    {},
                                    v,
                                    Siviglia.model.Root
                                );
                                this.autoUINode.append(v);
                            },
                            dump:function(node,params)
                            {
                                return this.controller.save();
                            }

                        }
                }

            }
    }
);

/* Se necesita una clase derivada de TYPESWITCHER para gestionar las regexes, que es un tipo
 raro de TypeSwitcher.
 Hay que declarar tanto la clase, como el painter asociado.El
 */
/*Siviglia.Utils.buildClass(
    {
        context: 'Siviglia.AutoUI',
        classes: {
            "RegexpSwitcher":
                {
                    "inherits":"Siviglia.AutoUI.TypeSwitcher",
                    construct: function (definition, parent, value,controller) {
                        this.TypeSwitcher({
                            "TYPE":"RegexpSwitcher",
                            "LABEL":"Urls:"
                        },parent,value,controller);
                        // Se inicializa el painter de este tipo, para que lo encuentre AutoUIPainter2
                        Siviglia.AutoUI.RegexpSwitcher.prototype.PAINTER="TypeSwitchPainter";
                    },
                    methods:
                        {
                            getTypeFromValue:function(v)
                            {
                                if(v==null)
                                    return null;
                                if(v.constructor.toString().match("Array"))
                                {
                                    if(v[0].constructor.toString().match("String"))
                                        return "RegexpArray";
                                    return "RegexpObject";

                                }
                                return null;
                            },
                            getAllowedTypes: function () {
                                return [{"LABEL":"Lista de Regexp","VALUE":"RegexpArray"},
                                    {"LABEL":"Include/Exclude","VALUE":"RegexpObject"}];
                            },
                            getCurrentType: function () {
                                return this.getTypeFromValue(this.getValue())
                            },
                            setType: function (typeName) {
                                if(typeName=="RegexpObject")
                                    this.setValue([{"match":".*","dontmatch":""}]);
                                else
                                    this.setValue([".*"]);

                            }
                        }
                }
        }
    });*/
            </script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>


</head>
<body id="<?php echo $v4name;?>" class="<?php echo 'site_'.$v4siteName.' page_'.$v4name.' ';?>nav-md">
<div style="display:none">
    <!-- HTML_DEPENDENCY Site WIDGETSTART 5a7e760d4d167 --><!-- HTML DEPENDENCY END 5a7e760d4d167 --><!-- HTML_DEPENDENCY Page WIDGETSTART 5a7e760d5e395 -->
<div sivWidget="Components.Tabbar" widgetParams="tabSource,tabLabel,type" widgetCode="Components.Tabbar" role="tabpanel">
    <ul class="nav nav-tabs bar_tabs" role="tablist" sivLoop="/*tabSource" contextIndex="current">
        <li role="presentation">
            <a role="tab" data-toggle="tab" sivCall="getTabLabel" sivParams='{"current":"/@current","index":"/@current-index"}' sivEvent="click" sivCallback="setCurrentTab"></a>
        </li>
    </ul>
    <div class="tab-content" sivLoop="/*tabSource" contextIndex="current">
        <div class="tab-pane fade" role="tabpanel" sivCall="getTabContent" sivParams='{"current":"/@current"}'>

        </div>
    </div>
</div>


<div sivWidget="Components.Box" class="x_panel">
    <div class="x_title">
        <h2 widgetNode="TITLE"></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                </ul>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" widgetNode="CONTENT">

    </div>
</div>

<div sivWidget="Components.SelectorOrNew" widgetParams="url,options,label,value" widgetCode="Components.SelectorOrNew">
        <div>
            <div sivId="combo" style="float:left">
            </div>
            <div style="float:left;margin-left:30px">
                <div class="form-group">
                    <div style="display:table-cell">
                        <input type="text" class="form-control" style="height:27px" sivId="newItem">
                    </div>
                    <div style="display:table-cell;padding-left:5px">
                        <button class="btn btn-primary" style="font-size:12px" sivEvent="click" sivCallback="onNew">Nuevo</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- HTML DEPENDENCY END 5a7e760d5e395 --><!-- HTML_DEPENDENCY Page WIDGETSTART 5a7e760d6148a -->

<div style="display:none">




    <div sivWidget="Reflection.Widgets.DefinitionForm" widgetParams="" widgetCode="Reflection.Widgets.DefinitionForm">
        <div class="x_panel">
    
        <div class="x_title">
            <h2><i class="fa"></i>Definiciones del sistema</h2>

        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                </ul>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    
    <div class="x_content">
        
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-6 col-sm-6">Cargar:</div>
                        <div class="col-md-6 col-sm-6 text-right">
                            <button class="btn btn-primary" value="Save" style="font-size:12px;margin-top:6px" sivEvent="click" sivCallback="save">Guardar</button>
                        </div>
                    </div>
                </div>





                    <div style="clear:both"></div>
                <div sivId="autoUINode"></div>
            
    </div>
    
</div>

    </div>


</div>

<!-- HTML DEPENDENCY END 5a7e760d6148a --><!-- HTML_DEPENDENCY Page WIDGETSTART 5a7e760d57f7f -->

<div id="" style="display:none">
    <div sivWidget="AUTOUI_FACTORY" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.Factory" class="WidgetFactory">
        <div class="factoryContainer"></div>
    </div>
    <div sivWidget="AUTOPAINTER_DictionaryType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.DictionaryPainter"
         class="DictionaryType ContainerType">
        <div class="panel panel-primary">
            <div class="panel-heading" sivValue="/*title" class="title"></div>
            <div class="panel-body">
                <!--<div class="container-fluid">-->
                <p class="text-info description" sivValue="/*description"></p>
                <div sivIf="/*hasSimpleType == false">
                <div class="row" style="margin-left:0px">
                    <div style="display:flex">
                    <div class="containerLabel" width="150px">
                        <div class="well">
                        <ul  class="nav nav-stacked" sivLoop="/*uinode/getKeys" contextIndex="current">
                            <li role="presentation">
                                <button class="btn btn-primary" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"key":"/@current"}'></button>
                                <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                            </li>
                        </ul>
                        </div>
                        <div class="newItemWidget" sivCall="buildNewItemWidget"></div>
                    </div>
                        <div sivIf="/*currentKey != null">
                        <div class="containerInput dictionaryInput">
                            <div class="currentWidget" sivCall="getInputFor" sivParams='{"key":"/*currentKey"}'>
                            </div>
                        </div>
                        </div>

                    </div>

                </div>
                </div>
                <div sivIf="/*hasSimpleType == true" >
                    <div  sivLoop="/*uinode/getKeys" contextIndex="current">
                        <div style="display:flex">
                        <div>
                            <span class="label label-primary" sivValue="/@current"></span>
                        </div>
                        <div>
                            <span sivCall="getInputFor" sivParams='{"key":"/@current"}'></span>
                        </div>
                        <div>
                            <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                        </div>
                        </div>
                    </div>
                    <div class="newItemWidget" sivCall="buildNewItemWidget"></div>
                </div>

                <!--</div>-->
            </div>
            <div class="actions">
                <div class="addItem" sivId="newItemNode"></div>
                <div style="float:right" class="saveNode" sivId="saveNode">
                    <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>

    <div sivWidget="AUTOPAINTER_FixedDictionaryType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FixedDictionaryPainter"
         class="DictionaryType ContainerType">
        <div class="panel panel-primary">
            <div class="panel-heading" sivValue="/*title" class="title"></div>
            <div class="panel-body">
                <p class="text-info description" sivValue="/*description"></p>
                <!--<div class="container-fluid">-->
                <div class="row" style="margin-bottom:10px;margin-left:0px">
                    <div class="containerLabel well" style="margin-right:5px">
                        <ul  class="nav nav-stacked" sivLoop="/*uinode/getKeys" contextIndex="current">
                            <li role="presentation">
                                <button class="btn btn-primary" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"key":"/@current"}'></button>
                                <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                            </li>
                        </ul>
                        <div class="newItemWidget" sivId="newItemNode" sivCall="buildNewItemWidget" ></div>
                    </div>
                    <div class="containerInput dictionaryInput" style="padding-right:10px;padding-left:10px">
                        <div class="currentWidget" sivCall="getInputFor" sivParams='{"key":"/*currentKey"}'>
                        </div>
                    </div>
                </div>
                <!--</div>-->
                <div class="actions">
                    <div class="addItem" sivId="newItemNode"></div>
                    <div style="float:right" class="saveNode" sivId="saveNode">
                        <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_ContainerType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ContainerPainter"
         class="ContainerType">
        <div class="panel panel-primary">
            <!--<div class="panel-heading" sivValue="/*title" class="title"></div>-->
            <div class="panel-body">
                <p class="text-info description" sivValue="/*description"></p>

                    <div class="inputs" sivLoop="/*uinode/getKeys" contextIndex="current">
                        <div style="margin:4px">
                            <label class="containerLabel text-primary"  class="control-label" sivCall="getLabel" sivParams='{"key":"/@current"}'></label>
                            <div sivIf="/*uinode/isSimpleType == false" class="containerInput">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>

                            <div sivIf="/*uinode/isSimpleType == true">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>

                        </div>
                    </div>
                    <div class="saveNode" sivId="saveNode">
                        <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                    </div>

            </div>

        </div>

    </div>

    <div sivWidget="AUTOPAINTER_StringType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.StringPainter" class="StringType">
        <div class="input-group" sivId="inputNode">
        </div>
    </div>
    <div sivWidget="AUTOPAINTER_BooleanType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.BooleanPainter" class="BooleanType">
        <div class="input-group" sivId="inputNode">
        </div>
    </div>
    <div sivWidget="AUTOPAINTER_ArrayType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ArrayPainter" class="ArrayType">
        <p class="text-info description" sivValue="/*description"></p>

        <div sivLoop="/*uinode/children" contextIndex="current" class="arrayValues" style="min-height:20px;margin-bottom:5px">
            <span class="label label-primary" style="margin-right:4px;font-size:14px">
                <span sivValue="/@current/value"></span>
                <span class="remove ion-close-circled " style="color:red;padding-left:5px" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
            </span>
        </div>

        <div  class="newItem" sivId="newItemNode"></div>
    </div>

    <div sivWidget="AUTOPAINTER_ObjectArrayType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ObjectArrayPainter" class="ArrayType">
        <p class="text-info description" sivValue="/*description"></p>
        <div class="row" style="margin-left:0px">

                <div sivIf="/*keyDirection == VERTICAL">
                    <div style="display:flex">
                    <div class="well">
                    <ul  class="nav nav-stacked" sivLoop="/*uinode/children" contextIndex="current">
                        <li role="presentation">
                            <button class="btn btn-primary" sivCall="getLabel" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"index":"/@current-index"}'></button>
                            <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current-index"}'></span>
                        </li>
                    </ul>
                    <div class="newItemWidget" sivId="newItemNode">
                        <input type="button" sivEvent="click" sivCallback="addItem">
                    </div>
                    </div>
                    </div>
                </div>
                <div sivIf="/*keyDirection == HORIZONTAL">
                    <div>
                    <span class="newItemWidget add" sivId="newItemNode" sivEvent="click" sivCallback="addItem">
                    </span>
                    <span  sivLoop="/*uinode/children" contextIndex="current">
                        <span class="label label-primary" style="display:inline-block;margin-right:4px;font-size:14px">
                            <span sivCall="getLabel"  sivEvent="click" sivCallback="onLabelClicked" sivParams='{"index":"/@current-index"}'></span>
                            <span class="removebutton" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current-index"}'></span>
                        </span>
                    </span>
                    </div>
                </div>
                <div class="containerInput dictionaryInput">
                    <div class="currentWidget">
                    </div>
                </div>

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_NewItem" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.NewItemPainter" class="NewItem">
        <div class="NewItemString">

                <div style="display:table-row">
                    <div style="display:table-cell">
                    <input style="float:left" type="text" class="form-control  addKey" placeholder="Nuevo" sivId="newItemString">
                    <select style="float:left" class="form-control" sivId="newItemSelector"></select>
                    </div>
                    <div style="display:table-cell;vertical-align: top;line-height: 1.0;font-size: 20px;"><div sivEvent="click" sivCallback="onAdd" class="add"></div></div>

                </div>


        </div>
        <div class="NewItemSelector" style="display:none">

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_TypeSwitcher" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.TypeSwitchPainter" class="TypeSwitchType">
        <div class="panel panel-primary">
            <!--<div class="panel-heading" sivValue="/*title" class="title"></div>-->
            <div class="panel-body">
                <div sivId="fieldContainer"></div>
                <div class="typeChanger">
                    <div sivId="typeSwitchSelector">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_SelectorType" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.SelectorPainter" class="SelectorType">
        <div class="input-group" sivId="inputNode">
        </div>
        <div class="NewItemSelector" style="display:none">

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_SubdefinitionType" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.SubdefinitionPainter" class="SubdefinitionType">
        <div sivId="subcontainer">

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_FixedPainter" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FixedPainter" class="FixedType">
        <div sivValue="/*uinode/value"></div>
    </div>

</div>
<!-- HTML DEPENDENCY END 5a7e760d57f7f -->
</div>

    <div class="container body">

    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>John Doe</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    
    <div class="menu_section">
            <h3>System</h3>
                <ul class="nav side-menu">
                    
                    <li>
                        <a><i class="fa fa-cubes"></i>Namespaces <span class="fa fa-chevron-down"></span></a>
                        
                            <ul class="nav child_menu">
                                <?php for($v1k=0;$v1k < $v1it->count();$v1k++){?>
                                    <li><a href="<?php $v1f=1;echo Registry::getService("router")->generateUrl("Namespaces",array('namespace'=>$v1it[$v1k]->name));?>"><?php echo $v1it[$v1k]->name;?></a></li>
                                <?php } ?>
                            </ul>
                        
                    </li>
                    
                </ul>
            
    </div>
    
    <div class="menu_section">
            <h3>Definitions</h3>
                <ul class="nav side-menu">
                    
                    <li>
                        <a><i class="fa fa-cubes"></i>Definitions <span class="fa fa-chevron-down"></span></a>
                        
                            <ul class="nav child_menu">
                                
                                    <li><a href="">Add</a></li>
                                
                            </ul>
                        
                    </li>
                    
                </ul>
            
    </div>
    
</div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="images/img.jpg" alt="">John Doe
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="javascript:;"> Profile</a></li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><a href="javascript:;">Help</a></li>
                                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="page-title">
            
                <div class="title_left"><h3></h3></div>
            
                <div class="title_right"></div>
            
            </div>
            <div class="clearfix"></div>
            
        <div sivView="Reflection.Widgets.DefinitionForm"></div>
    
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>



    <script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/build/js/custom.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-BODYEND-<?php echo $__serialized__bundle__Site;?>.js" ></script>

</body>
</html>
