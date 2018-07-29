<?php
   $v10site=\Registry::getService("site");
   $v10ds=\lib\datasource\DataSourceFactory::getDataSource('\model\reflection\ReflectorFactory','NamespaceList');
   $v10it=$v10ds->fetchAll();
?><?php
    $v14page=\Registry::getService("page");
    $v14site=\Registry::getService("site");
    $v14name=$v14page->getPageName();
    $v14siteName=$v14site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v14site->getDefaultIso();?>">
<head>
    <title>Testing</title><?php $__serialized__bundle__Site=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Site.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css"/>
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
                    baseUrl:'<?php echo $v10site->getCanonicalUrl();?>/',
                    publicUrl:'<?php echo $v10site->getCanonicalUrl();?>',
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
                                            "loadDefinition":"/Reflection/Definitions/[%name%]"
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
                        "FixedDictionaryType":"FixedDictionaryPainter",
                        "FormContainer":"FormContainerPainter"
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
                    var m=this;
                    params.uinode.addListener("sourceChange",this,"initializeInput")
                },
                initialize:function(params) {
                    this.initializeInput();
                },

                initializeInput:function()
                {
                    var m=this;
                    this.uinode.getSourceValues().then(function(vals) {
                        if (m.inputF) {
                            m.inputF.destruct();
                        }
                        m.inputF = new Siviglia.Forms.JQuery.Inputs.Enum(null, m.inputNode);
                        var v = m.uinode.definition;
                        var localDef = {}
                        for (var k in v)
                            localDef[k] = v[k];
                        localDef["VALUES"] = vals;
                        m.inputF.sivInitialize(localDef, m.uinode.getValue(), {});

                        m.inputF.on("change", function (node) {
                            if (m.syntheticChange)
                                return;
                            m.syntheticChange = true;
                            m.uinode.setValue(m.inputF.getValue());
                            m.syntheticChange = false;

                        })
                    });
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
        FormContainerPainter:
            {
              //listContainer,viewContainer,groups
                inherits:'BasePainter',
                methods:
                    {
                        preInitialize:function(params)
                        {
                            this.BasePainter$preInitialize(params);
                            this.value=params.uinode.getValue()
                            this.groups=params.uinode.definition.GROUPS;
                        },
                        initialize:function(params)
                        {
                            this.params=params;
                            this.paintValue();
                        },
                        paintValue:function()
                        {

                        },
                        getHLink:function(node,params)
                        {
                            var c=params.current;
                            node.attr("href","#"+c);
                        },
                        getContents:function(node,params)
                        {
                            node.attr("id",params.current);
                            for(var k in this.uinode.definition.GROUPS[params.current]["CONTENTS"])
                            {
                                var currentWidget=this.painterFactory.factory(this.uinode.groups[params.current][k],this.uinode.parent, {});
                                node.append(currentWidget.rootNode);
                            }
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
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>


</head>
<body id="<?php echo $v14name;?>" class="<?php echo 'site_'.$v14siteName.' page_'.$v14name.' ';?>nav-md">
<div style="display:none">
    <!-- HTML_DEPENDENCY Site WIDGETSTART 5a888b4b562ae --><!-- HTML DEPENDENCY END 5a888b4b562ae --><!-- HTML_DEPENDENCY Site WIDGETSTART 5a888b4b449bb -->

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

                    <div class="inputs container" sivLoop="/*uinode/getKeys" contextIndex="current">
                        <div class="row">
                            <div class="col col-sm-1 col-md-1">
                            <label class="text-primary"  class="control-label" sivCall="getLabel" sivParams='{"key":"/@current"}'></label>
                            </div>
                            <div class="col col-sm-11 col-md-11">
                            <div sivIf="/*uinode/isSimpleType == false" class="containerInput">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>

                            <div sivIf="/*uinode/isSimpleType == true">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>
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

    <div sivWidget="AUTOPAINTER_FormContainer" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FormContainerPainter" class="FormContainerType">
        <div class="container">
            <div class="row">
                <div sivId="listContainer" class="col col-md-2 col-sm-2">
                    <ul class="nav nav-tabs tabs-left" sivLoop="/*groups" contextIndex="current">
                        <li class="nav-item">
                            <a class="nav-link" sivCall="getHLink" data-toggle="tab" sivValue="/@current/LABEL" sivParams='{"current":"/@current-index"}'></a>
                        </li>
                </ul>
                </div>
                    <div sivId="viewContainer" class="col col-md-10 col-sm-10">
                        <div class="tab-content card" sivLoop="/*groups" contextIndex="current">
                            <div class="tab-pane" sivCall="getContents" sivParams='{"current":"/@current-index"}' role="tabpanel">

                            </div>
                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>
<!-- HTML DEPENDENCY END 5a888b4b449bb -->
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
                                <?php for($v10k=0;$v10k < $v10it->count();$v10k++){?>
                                    <li><a href="<?php $v10f=1;echo Registry::getService("router")->generateUrl("Namespaces",array('namespace'=>$v10it[$v10k]->name));?>"><?php echo $v10it[$v10k]->name;?></a></li>
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
                    <ul class="nav navbar-nav">
                        <li><a><h5 id="sectionTitle" style="font-size: 15px;font-weight: bold;color: #73899c;margin-top: 7px;">SECTION TITLE</h5></a></li>
                    </ul>

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
            <?php
            global $SERIALIZERS;
            $v1currentPage=Registry::$registry["currentPage"];
            $v1params=Registry::$registry["params"];
            $v1serializer=\lib\storage\StorageFactory::getSerializerByName('web');
            $v1serializer->useDataSpace($SERIALIZERS["web"]["ADDRESS"]["database"]["NAME"]);
?><?php $v8currentPage=$v1currentPage;
$v8object='/web/Site';
$v8dsName='FullList';
$v8serializer=$v1serializer;
$v8params=$v1params;
$v8iterator=&$v1iterator;
 ?><div style="border:1px solid #DDDDDD;background-color:#EEEEEE">
   <div style="background-color:#CCCCCC">
        Titulo de la listaDescripcion de la lista

   </div>
   <div>   
       <table width="100%">
              
    
           <tr>
            <th  style="border-bottom:1px solid #AAAAAA;">id_site</th><th  style="border-bottom:1px solid #AAAAAA;">Host</th><th  style="border-bottom:1px solid #AAAAAA;">Canonical url</th><th  style="border-bottom:1px solid #AAAAAA;">Has SSL</th><th  style="border-bottom:1px solid #AAAAAA;">namespace</th><th  style="border-bottom:1px solid #AAAAAA;">name</th>
           </tr>
         <?php 
$v9object= $v8object;
$v9name= $v8dsName;
$v9serializer= $v8serializer;
$v9params= $v8params;

?><?php

        if($v9object)
        {
            $v9ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v9object),$v9name);
            if($v9params)
            {
                $v9defDs=$v9ds->getDefinition();

                if(is_object($v9params))
                {
                    $v9def=$v9params->getDefinition();

                    if(isset($v9defDs["INDEXFIELDS"]))
                    {
                        foreach($v9defDs["INDEXFIELDS"] as $v9key=>$v9value)
                            $v9ds->{$v9key}=$v9params->{$v9key};

                    }
                    if(isset($v9defDs["PARAMS"]))
                    {
                        foreach($v9defDs["PARAMS"] as $v9key=>$v9value)
                        {
                            if(isset($v9def["FIELDS"][$v9key]))
                                $v9ds->{$v9key}=$v9params->{$v9key};
                        }
                    }
                }
                else
                {
                    foreach($v9defDs["PARAMS"] as $v9key=>$v9value)
                    {
                        if(isset($v9params[$v9key]))
                            $v9ds->{$v9key}=$v9params[$v9key];
                    }
                }
            }
            if(isset($v9dsParams))
            {
                $v9pagingParams=$v9ds->getPagingParameters();
                foreach($v9dsParams as $v9key=>$v9value)
                    $v9pagingParams->{$v9key}=$v9value;
            }

            $v9ds->initialize();

         }
        ?><?php 
$v9iterator= &$v8iterator;

?><?php
    
      global $globalPath;
      global $globalContext;
      
      if(isset($v9subDs))
          $v9it=$globalPath->getPath($v9subDs,$globalContext);
      else
          $v9it=$v9ds->fetchAll();

      $globalPath->addPath($v9name,$v9it);
      $v9nItems=$v9it->count();      
      
      for($v9k=0;$v9k<$v9nItems;$v9k++)
      {
          $globalPath->addPath($v9name,$v9it[$v9k]);
          $v9iterator=$v9it[$v9k];

      
     ?>
                    <tr>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v2name='id_site';
$v2model=$v1iterator;
 ?><?php echo $v2model->{$v2name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v3name='host';
$v3model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v3model->{$v3name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v4name='canonical_url';
$v4model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v4model->{$v4name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v5name='hasSSL';
$v5model=$v1iterator;
 ?><?php if($v5model->{$v5name}){?>
    <div style="width:20px;height:20px;background-color:green"></div>
<?php } else{ ?>
    <div style="width:20px;height:20px;background-color:blue"></div>
<?php }?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v6name='namespace';
$v6model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v6model->{$v6name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v7name='websiteName';
$v7model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v7model->{$v7name};?></span>

                        </td>
                        
                    </tr>
                    <?php }?>
    </table>
    </div>
</div>


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
