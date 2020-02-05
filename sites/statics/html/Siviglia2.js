var Siviglia2=Siviglia2 || {};

Siviglia2.EventManager=class {

    constructor() {
    }
    destructor() {
        // If this destructor was called while this object was notifying its listeners,
        // simply set a flag and return.
        if (this.notifying) {
            this.mustDestroy = true;
            return;
        }
        this.listeners = null;
    }

    addListener(evType, object, method, param, target) {
            if (!this.listeners)this.listeners = {};
            if (!this.listeners[evType])
                this.listeners[evType] = [];

            var k;
            for (k = 0; k < this.listeners[evType].length; k++) {
                if (this.listeners[evType][k].obj == object && this.listeners[evType][k].method == method) {
                    return;
                }
            }
            this.listeners[evType].push({
                obj: object,
                method: method,
                param: param,
                target: target
            });

        }

    removeListener(evType, object, method, target) {
            if (!this.listeners)return;
            if (!this.listeners[evType])return;
            var k, curL;
            for (k = 0; k < this.listeners[evType].length; k++) {
                curL = this.listeners[evType][k];
                if (curL.obj == object && (!method || (method == curL.method))) {
                    if (target) {
                        if (curL.target != target)
                            continue;
                    }
                    this.listeners[evType].splice(k, 1);
                    return;
                }
            }
        }
    removeListeners(object) {
            if (!this.listeners)return;
            var k, j;
            for (k in this.listeners) {
                for (j = 0; j < this.listeners[k].length; j++) {
                    if (this.listeners[k][j].obj == object) {
                        this.listeners[k].splice(j, 1);
                        j--;
                    }
                }
            }
        }
    notify(evType, data, target) {
            if (!this.listeners)return;
            if (!this.listeners[evType]) {
                return;
            }
            this.notifying = true;
            var k;
            var obj;
            for (k = 0; k < this.listeners[evType].length; k++) {
                obj = this.listeners[evType][k];
                if (obj.obj) {
                    if(typeof obj.obj=="function")
                    {
                        obj.obj(evType,data,obj.param,target);
                    }
                    else {
                        if (!obj.obj[obj.method])
                            continue;
                        obj.obj[obj.method](evType, data, obj.param, target);
                    }
                }
                else {
                    obj.method(evType, data, obj.param, target);
                }
            }
            // The following is a protection code; if marks this object as "notifying",so, if as part of the notification, this object
            // is destroyed, it will not destroy the listeners, but set the mustDestroy flag to true.
            this.notifying = false;
            if (this.mustDestroy) {
                this.listeners = null;
            }
        }

        fireEvent(event, data, target) {
            if(data!==null) {
                if (typeof data != "undefined")
                    data.target = target;
                else
                    data = {
                        target: target
                    };
                data.src = this;
            }
            this.notify(event, data, target);
        }
}

Siviglia2.eventify = function(obj,prop)
{
    if(obj.hasOwnProperty("$"+prop))
        return;
    var eventListener=new Siviglia.EventManager();
    Object.defineProperty(obj,"$".prop,{
        enumerable:false,
        get:function(){
            return eventListener;
        }
    });
    Object.defineProperty(obj,prop,{
        enumerable:true,
        set:function(val)
        {
            obj[prop]=val;

        }
    })
}

Siviglia2.UI={}
Siviglia2.UI.Widget=class extends HTMLElement{
    constructor(tagName,templateId)
    {
        super();
        var template = document.getElementById(templateId);
        var templateContent = template.content;
        var m=this;
        $.when(this.preInitialize()).then(function(){
            var shadowRoot = m.attachShadow({mode: 'open'})
            .appendChild(templateContent.cloneNode(true));
        })
    }
    connectedCallback()
    {
        this.recurseHTML(this);
    }
    preInitialize()
    {
        var p=$.Deferred();
        p.resolve();
        return p;
    }
}



Siviglia2.UI.ExpandoManager = class {
    constructor(pathRoot, contextObj) {
        this.pathRoot = pathRoot;
        this.context = contextObj;
        this.expandos = [];
        this.destroyed = false;
    }
    destruct() {
        //console.debug("******DESTROYING EXPANDOS");
        this.destroyExpandos();
        this.pathRoot = null;
        this.expandos = null;
        this.destroyed=true;
    }

    parse(htmlNode, caller, removeAttr, keepObjects) {
            if (this.expandos.length > 0 && !keepObjects) {
                this.destroyExpandos();
                this.expandos = [];
            }
            var manager = this;
            var k;
            var views = [];
            for (k = 0; k < htmlNode.length; k++) {

                if (htmlNode[k].nodeType == 3 || htmlNode[k].nodeType == 8) // TEXT -- HTML Comment
                {
                    continue;
                }
                //if(htmlNode[k].getAttribute("SivigliaParsed"))continue;


                Siviglia.Utils.recurseHTML(htmlNode[k],
                    function (node) {
                        var k, attr;
                        var retValue = true;
                        if(node.getAttribute("sivId"))
                        {
                            var cWidget=Siviglia.UI.Dom.Expando.WidgetExpando.prototype.widgetStack.pop();
                            var target=cWidget;
                            if(cWidget!=cWidget.viewObject)
                                target=cWidget.viewObject;

                            target[node.getAttribute("sivId")]=$(node);
                            Siviglia.UI.Dom.Expando.WidgetExpando.prototype.widgetStack.push(cWidget);
                        }
                        for (k in manager.installedExpandos) {
                            if (!node.getAttribute) {
                                //console.dir(node);
                                break;
                            }

                            attr = node.getAttribute(k);
                            if (attr) {

                                if (k == "sivView") {
                                    views.push(node);

                                    continue;
                                }

                                var curExpando = $(node).data();
                                if (curExpando["expando_" + attr]) {
                                    curExpando["expando_" + attr].onListener();
                                    return false;
                                }
                                else {
                                    retValue =  manager.addExpando(node, manager.installedExpandos[k], caller) && retValue;
                                }
                                /*if(removeAttr)
                                 node.removeAttribute(k);*/
                            }
                        }
                        return retValue;
                    });
                // Se parsean ahora las vistas.

            }
            for (var k = views.length - 1; k >= 0; k--) {
                if ($(views[k]).data("parsed"))
                    continue;
                manager.addExpando(views[k], 'ViewExpando', caller);
                if (removeAttr)
                    views[k].removeAttribute('sivView');
            }
            return false;
        }
        setOwner(owner)
        {
            this.__owner=owner;
        }
        addExpando(node, expType, caller) {
            var newExpando = new Siviglia.UI.Dom.Expando[expType]();
            var result = newExpando._initialize($(node), this, this.pathRoot, this.context, caller);
            this.expandos.push(newExpando);
            $(node).data("expando_" + expType, newExpando);
            //node.setAttribute("expando_"+expType,newExpando);
            return result;
        }
        resetExpandos() {
            var k;
            for (k = 0; k < this.expandos.length; k++)
                this.expandos[k].reset();
        }
        destroyExpandos() {
            var k;
            if (!this.expandos)return;
            for (k = 0; k < this.expandos.length; k++)
                this.expandos[k].destruct();
        }
        updateExpandos() {
            var k;
            if (!this.expandos)return;

            for (k = 0; k < this.expandos.length; k++) {
                if (this.expandos[k].listener) {
                    this.expandos[k].listener.onChange();
                }
            }
        }
    }
