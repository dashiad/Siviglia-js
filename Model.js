// Nombres de modelos: App.<layer>.<objetoPadre>.Model
Siviglia.globals = {};
Siviglia.Model={};

Siviglia.Utils.buildClass({
      context: "Siviglia",
      classes: {
          Cache: {
              construct: function () {
                  this.cache = {};
              },
              methods:
                {
                    add: function (type, key, value) {
                        if (typeof this.cache[type] == "undefined")
                            this.cache[type] = {};
                        this.cache[type][key] = value;
                    },
                    get: function (type, key) {
                        if (typeof this.cache[type] == "undefined")
                            return null;
                        return Siviglia.issetOr(this.cache[type][key],null);
                    },
                    delete: function (type, key) {
                        this.cache[type][key] = null;
                    }
                }
          }
      }
  }
);
var Cache = new Siviglia.Cache();
Siviglia.globals.Cache = Cache;


Siviglia.globals.FileServer=function(def)
{
    this.def=def;
    this.getUrl=function(subPath){return this.def.baseUrl+"/"+subPath;}
}
Siviglia.globals.FileServerFactory=function(fileServer)
{
    if(typeof Siviglia.config !== "undefined" && typeof Siviglia.config.FileServers!=="undefined" && typeof Siviglia.config.FileServers[fileServer]!=="undefined")
        return new Siviglia.globals.FileServer(Siviglia.config.FileServers[fileServer]);
    // No dejamos que todo pete si no existe el fileserver. Devolvemos el path local.
    console.error("Siviglia: requesting unknown fileServer:"+fileServer);
    return new FileServer({"baseUrl":""});
}


Siviglia.Model.initialize = function (config) {

    Siviglia.Model.config = config;
    Siviglia.Model.mapper = new Siviglia.Model[config.mapper + "Mapper"](config);
    Siviglia.Model.loader = new Siviglia.Model.Loader();
}

Siviglia.Utils.buildClass({
    context: 'Siviglia.Model',
    classes: {
        ModelDescriptor: {
            construct: function (mname) {
                if (typeof mname == "string") {
                    var objPath = mname.replace(/\\/g, '/').split("/");
                    if (objPath[0] == "")
                        objPath = objPath.splice(1);
                    if (objPath[0] != "model")
                        objPath.unshift("model");
                    this.namespace = objPath[1];
                    var l = objPath.length;
                    this.model = objPath[l - 1];
                    objPath.shift();
                    objPath.shift();
                    objPath.pop();
                    this.parentModel = objPath.length == 0 ? null : objPath;
                } else {
                    var src = mname;
                    if (src.definition)
                        src = mname.definition;

                    this.model = src.name;
                    this.parentModel = src.parentObject;

                    this.namespace = src.layer;
                }
                this.parentModelStr = this.parentModel ? this.parentModel.join("/") + "/" : "";
                this.config = Siviglia.Model.mapper.config;
            },
            methods: {
                getObjectPath: function () {
                    return Siviglia.Model.mapper.getObjectPath(this);
                },
                getObjectUrl: function () {
                    return Siviglia.Model.mapper.getObjectUrl(this);
                },
                getCanonical: function () {
                    return '/model/' + this.namespace + '/' + this.parentModelStr + this.model;
                },
                getCanonicalDotted: function () {
                    return this.getCanonical().substr(1).replace(/\//g, '.');
                },
                getDataSourceUrl: function (datasource, id, params,settings) {
                    return Siviglia.Model.mapper.getDataSourceUrl(this, datasource, id, params,settings);
                },
                getJSModelPath: function () {
                    return Siviglia.Model.mapper.getJSModelPath(this);
                },
                getActionUrl: function (actionName) {
                    return Siviglia.Model.mapper.getActionUrl(this.getCanonical(), actionName);
                },
                getFormUrl:function(formName,keys)
                {
                    return Siviglia.Model.mapper.getFormUrl(this.getCanonical(),formName,keys);
                },
                getModelNamespace: function () {
                    var s = this.getCanonicalDotted();
                    return s[0].toUpperCase() + s.substr(1);
                },
                getModelMetaPath:function(){
                    var m=this.getCanonical();
                    return Siviglia.Model.mapper.getModelMetaPath(m);
                },
                getDataSourceMetaDataUrl:function(dsName)
                {
                    var m=this.getCanonical();
                    return Siviglia.Model.mapper.getDataSourceMetaPath(m,dsName);
                }

            }
        },
        SivigliaMapper: {
            construct: function (config) {
                this.config = config;
            },
            methods: {
                getMetaPath: function (options) {
                    return this.config.metadataUrl;
                },
                getModelMetaPath: function (model) {
                    return this.getMetaPath() + model + "/definition";
                },
                getDataSourceMetaPath:function(model,dsName)
                {
                    return this.getMetaPath()+model+"/datasources/"+dsName+"/definition";
                },
                getModel: function (spec) {
                    var m = new Siviglia.Model.ModelDescriptor(spec);
                    return this.config.baseUrl + "/js/" + this.config.mapper + "/" + this.getObjectPath(m) + "/Model.js";
                },
                getFormUrl: function (model, form, keys) {
                    var query="";
                    var n={};
                    if(typeof(keys)!=="undefined" && keys!==null)
                    {
                        for(var k in keys)
                        {
                            if(keys[k]!=null)
                                n[k]=keys[k];
                        }
                    }
                    query =$.param(n);
                    return this.config.baseUrl + "form" + model + '/' +  form + '?output=json' + "&" + query;
                },
                getDataSourceUrl: function (model, datasource, id, params,settings) {
                    var query="";
                    var n={};
                    if(typeof(params)!=="undefined" && params!==null)
                    {

                        for(var k in params)
                        {
                            if(params[k]!=null)
                                n[k]=params[k];
                        }
                    }
                    var output="json";
                    if(typeof(settings)!=="undefined" && settings!==null)
                    {

                        for(var k in settings)
                        {
                            if(settings[k]!=null)
                                n[k]=settings[k];
                            if(k==="output")
                                output=settings[k];
                        }
                    }

                    query =$.param(n);
                    return this.config.baseUrl + "datasource" + model.getCanonical() + '/' + (id ? id + '/' : '') + datasource + '?output=' +output + "&" + query;
                },
                getJSModelUrl: function (model) {
                    var m = new Siviglia.Model.ModelDescriptor(model);
                    return this.config.baseUrl + "/js/" + this.config.mapper + m.getCanonical() + "/Model.js";
                },
                getActionUrl: function (model, actionName) {
                    return this.config.baseUrl + '/action';
                }
            }
        },
        Transport: {
            methods:
              {
                  doGet: function(url) {
                      var h = $.Deferred();
                      $.ajax({
                          xhrFields: {
                              withCredentials: true
                          },
                          async: true,
                          dataType: 'json',
                          data: '',
                          type: 'GET',
                          url: url,
                          success: function (response) {

                              if (response.success || !response.error)
                                  return h.resolve(response);

                              h.reject(response);
                              if (response.error == 2) {
                                  alert("La sesion ha expirado.Debe hacer login de nuevo.");
                                  document.location.reload();
                              }
                          },
                          error: function (error) {
                              if (error.error && error.error == 2) {
                                  alert("La sesion ha expirado.Debe hacer login de nuevo.")
                                  document.location.reload();
                                  return;
                              }
                              h.reject(error);
                          }
                      });
                      return h;
                  },
                  doSyncGet: function(url) {
                      var h = $.Deferred();
                      var result={};
                      $.ajax({
                          xhrFields: {
                              withCredentials: true
                          },
                          async: false,
                          dataType: 'json',
                          data: '',
                          type: 'GET',
                          url: url,
                          success: function (response) {

                              result={error:0,data:response};

                          },
                          error: function (error) {
                              result={error:error,data:null};
                          }
                      });
                      return result;
                  },

                  doPost: function (url, data) {
                      var h = $.Deferred();
                      $.ajax({
                          async: true,
                          dataType: 'json',
                          data: data,
                          type: 'POST',
                          url: url,
                          xhrFields: {
                              withCredentials: true
                          },
                          success: function (response) {
                              if (response.success || !response.error)
                                  return h.resolve(response);
                              h.reject(response);
                          },
                          error: function (error) {
                              h.reject(error);
                          }
                      });
                      return h;
                  }
              }
        },
        Loader: {
            construct: function (config) {
                this.config = config;
                this.transport = new Siviglia.Model.Transport();
            },
            methods:
              {
                  getModelDefinition:function(model)
                  {
                      var dsc=new Siviglia.Model.ModelDescriptor(model);
                      var cached=Siviglia.globals.Cache.get("ModelDefinition",dsc.getCanonicalDotted());
                      if(cached)
                      {
                          return cached;
                      }
                      // La cargamos de remoto, pero de forma sincrona
                      // primero hay que componer la URL de destino, que es:

                      var url=dsc.getModelMetaPath();
                      var result=this.transport.doSyncGet(url);
                      if(result.error==0) {
                          Siviglia.globals.Cache.add("ModelDefinition", dsc.getCanonicalDotted(), result.data);
                          return result.data;
                      }
                      return null;
                  },
                  getDatasourceMeta:function(model,name)
                  {
                      var descriptor=new Siviglia.Model.ModelDescriptor(model);
                      var dsMetaUrl=descriptor.getDataSourceMetaDataUrl(name);

                      var cached=Siviglia.globals.Cache.get("DataSourceMeta",dsMetaUrl);
                      if(cached)
                      {
                          return cached;
                      }
                      var transport = new Siviglia.Model.Transport();
                      var metaResponse=transport.doSyncGet(dsMetaUrl);
                      var meta=metaResponse.data;
                      Siviglia.globals.Cache.add("DataSourceMeta", dsMetaUrl, meta);
                      return meta;


                  },

                  getModel: function (model, id, datasource) {


                      // Se ve si existe el objeto.
                      var instance = this._getInstanceForModel(model);
                      var m=this;
                      var h=$.Deferred();
                      var p = $.Deferred();

                      // Si la clase para este modelo ya existe, resolvemos la primera promesa.
                      if (instance != null)
                          h.resolve(instance);
                      else
                      {
                          // Si la clase para este modelo no existe, se intenta cargar de remoto.

                          var url=Siviglia.Model.mapper.getJSModelUrl(model);
                          Siviglia.Utils.load([url]).then(function(){
                              // Se vuelve a intentar encontrar la clase para este modelo.Ahora deberia existir.
                              instance=m._getInstanceForModel(model);
                              if(instance==null)
                              {
                                  // Si aqui la instancia esta a nula, es que la clase cargada no era la correcta.
                                  console.error("La clase para el modelo "+model+" no tiene el nombre correcto");
                                  h.reject();
                              }
                              Siviglia.globals.Cache.add("ModelDefinition",instance.getDescriptor().getCanonicalDotted(),instance.__getDefinition());
                              // Aqui se resuelve la primera promesa
                              h.resolve(instance);
                          },function(){h.reject()});
                      }

                      // Se espera a que la primera promesa se resuelva.
                      h.then(function(instance){
                            // Si no se paso ningun id para este modelo, resolvemos la segunda promesa.
                            if(typeof id=="undefined" || id===null)
                                p.resolve(instance);
                            else
                            {
                                // Si habia un id, se carga el datasource con los datos del modelo.
                                m._loadModel(instance,id,datasource).then(function(){
                                    // Y una vez cargado , se resuelve la segunda promesa.
                                    p.resolve(instance);
                                },function(){p.reject()});
                            }


                        },
                        function()
                        {
                            p.reject();
                        }
                      );
                      // Se retorna la segunda promesa.
                      return p;
                  },

                  _getInstanceForModel: function (model) {
                      var mName = new Siviglia.Model.ModelDescriptor(model);
                      var namespaced = mName.getModelNamespace();
                      var obj = Siviglia.Utils.stringToContextAndObject(namespaced);
                      if (typeof obj.context[obj.object] == "undefined") {

                          console.log("No existe localmente la clase " + namespaced + " para el modelo " + model);
                          return null;
                      }
                      return new obj.context[obj.object]();
                  },
                  /*
                     Esta funcion se llama cuando se sabe que la clase js del modelo
                     ya existe, sea porque se ha cargado, sea porque existia en la cache.
                   */
                  _loadModel: function (instance, id, datasource) {
                      var h = $.Deferred();

                      // Se ve si existe el objeto.
                      var m = this;
                      if (typeof datasource == "undefined")
                          datasource = "View";
                      // Se obtiene, del modelo, los campos indice.

                      var indexes = instance.getIndexFields();
                      var params = {};
                      if (indexes.length == 1)
                          params[indexes[0]] = id;
                      else {
                          for (var k = 0; k < indexes.length; k++) {
                              params[indexes[k]] = id[k];
                          }
                      }
                      var ds=m.getDataSource(instance.getName(), datasource, params);
                      ds.refresh().then(function (r) {
                          if (ds.data.length != 1) {
                              h.reject();
                          }
                          instance.setValue(ds.data[0]);
                          h.resolve(instance);
                      });
                      return h;
                  },
                  /*
                      El formato de options es:
                      {sort:[{attribute:, descending:(true/false)}],page:,pagination:}}
                   */
                  getDataSource: function (model, name, params, options) {
                      return new Siviglia.Model.DataSource(model,name,params,null);
                  },
                  getForm:function(model,name,keys)
                  {
                      var formFactory=new Siviglia.Model.FormFactory();
                      return formFactory.getForm(model,name,keys);
                  },
                  doAction: function (keys, data, objectName, actionName) {
                      var mName = new Siviglia.Model.ModelDescriptor(objectName);

                      var actionFormat = {
                          "MODEL": objectName,
                          "FORM": actionName,
                          "FIELDS": data

                      };
                      if (keys) {
                          actionFormat["KEYS"] = keys;
                      }
                      var nd = new Date();
                      var p = $.Deferred();
                      var url = mName.getActionUrl(actionName);
                      var transport = new Siviglia.Model.Transport();
                      transport.doPost(url + '?output=json&nc=' + nd.getTime(),
                        {json: JSON.stringify(actionFormat)}).then(
                        function (response) {
                            p.resolve(response);
                            return response;
                        },
                        function (error) {
                            p.reject(error);
                            throw error;
                        }
                      );
                      return p;
                  }
              }
        },
        FormFactory:{
            construct:function(){},
            methods: {
                getForm: function (model, name, keys) {
                    var loadedPromise = $.Deferred();
                    var descriptor = new Siviglia.Model.ModelDescriptor(model);
                    var formUrl = descriptor.getFormUrl(name, keys);
                    var transport = new Siviglia.Model.Transport();
                    transport.doGet(formUrl).then(function(result){
                        if(result.error==0)
                        {
                            var definition=result.definition;
                            var bto=new Siviglia.Model.Form(model,name,keys,result.form.definition,result.form.value);
                            loadedPromise.resolve(bto);
                        }
                        else
                            loadedPromise.reject();
                    })
                    return loadedPromise;
                }
            }
        },
        Form: {
            inherits: 'Siviglia.model.BaseTypedObject',
            construct: function (model, name, keys,definition,value) {
                this.__model=model;
                this.__fname=name;
                this.__keys=keys;
                this.BaseTypedObject(definition,null,false);
                this.ready().then(function(){
                    this.apply(value,Siviglia.types.BaseType.VALIDATION_MODE_NONE);
                }.bind(this));
            },
            methods:
              {

                  submit:function()
                  {
                      this.save();
                      var i = Siviglia.issetOr(this.getDefinition().INDEXFIELDS,null);
                      var myIndexes = {};
                      var vals = this.getPlainValue();
                      if(i!=null) {
                          for (var k = 0; k < i.length; k++) {
                              if (!this.type["*" + i[k]].__isEmpty()) {
                                  myIndexes[i[k]] = this[i[k]];
                              } else {
                                  myIndexes = null;
                              }
                              delete vals[i[k]];
                          }
                      }
                      return Siviglia.Model.loader.doAction(myIndexes, vals, this.__model, this.__fname)
                  }
              }
        },
        DataSource:
          {
              inherits: 'Siviglia.model.BaseTypedObject',
              construct: function (model, name, params, response) {
                  var meta;
                  this.__frozen=false;
                  this.__model=model;
                  this.__dsname=name;
                  this.__params=params;
                  this.__promises={};
                  this.__busListenerSet=false;
                  this.currentPromise=null;
                  this.nextPromise=null;
                  this.wampService=null;

                  this.__identifier=Siviglia.Model.getAppId();
                  if(typeof response=="undefined" || response==null)
                  {
                      meta=Siviglia.Model.loader.getDatasourceMeta(model,name);
                  }
                  else
                      meta = response.definition;
                  this.meta=meta;
                  var paramsDef = meta.PARAMS;
                  var fieldsDef = meta.FIELDS;

                  // Se construye la definicion de este objeto.
                  // La definicion consiste en:
                  // Los parametros, como un container
                  // Los parametros de entrada/salida, como campos.
                  // Estos parametros son el count, la paginacion, los criterios de ordenacion,etc
                  // los datos, que son un array de contenedores con los datos de respuesta.
                  var definition = {
                      "FIELDS": {
                          "params": {
                              "TYPE": "Container",
                              "FIELDS": paramsDef
                          },
                          "settings": {
                              "TYPE":"Container",
                              "FIELDS": {
                                  "__start":{"TYPE":"Integer","DEFAULT":0},
                                  "__count":{"TYPE":"Integer","DEFAULT":10},
                                  "__sort": {"TYPE": "String"},
                                  "__sortDir": {"TYPE": "Enum", "VALUES": ["ASC", "DESC"], "DEFAULT": "ASC"},
                                  "__sort1": {"TYPE": "String"},
                                  "__sortDir1": {"TYPE": "Enum", "VALUES": ["ASC", "DESC"], "DEFAULT": "ASC"},
                                  "__group": {"TYPE": "String", "MAXLENGTH": 30},
                                  "__groupParam": {"TYPE": "String", "MAXLENGTH": 30},
                                  "__groupMin": {"TYPE": "String", "MAXLENGTH": 30},
                                  "__groupMax": {"TYPE": "String", "MAXLENGTH": 30},
                                  "__accumulated": {"TYPE": "Boolean"},
                                  "__partialAccumul": {"TYPE": "Boolean"},
                                  "__autoInclude": {"TYPE": "String"}
                              }
                          },
                          "data": {
                              "TYPE": "Array",
                              "ELEMENTS": {
                                  "TYPE": "Container",
                                  "FIELDS": fieldsDef
                              }
                          },
                          "count": {"TYPE": "Integer"},
                          "start": {"TYPE": "Integer"},
                          "end": {"TYPE": "Integer"}
                      }
                  };
                  if(typeof meta.TYPES !=="undefined")
                      definition["TYPES"]=meta.TYPES;
                  this.BaseTypedObject(definition,null);

                  if(params===null)
                      params={};
                  this["*params"].setValue(params);

                  if(typeof response!=="undefined" && response!==null) {
                      this.onResponse(response);

                  }

                  // Se aniaden listeners en todos los campos.
                  for(var k in definition.FIELDS.params.FIELDS)
                  {
                      this.params["*"+k].addListener("CHANGE",this,"refresh","Datasource-params");

                  }
                  this.settings={};
                  for(var k in definition.FIELDS.settings.FIELDS)
                      this.settings["*"+k].addListener("CHANGE",this,"refresh","Datasource-Settings");
                  // Organizamos los campos que hay en el datasource,
                  var modelFields={};
                  for(var k in meta.FIELDS)
                  {
                      var cField=meta.FIELDS[k];
                      if(typeof cField.TYPE==="undefined" &&
                        typeof cField.MODEL !=="undefined")
                      {
                          if(typeof modelFields[cField.MODEL]==="undefined")
                              modelFields[cField.MODEL]=[];
                          modelFields[cField.MODEL].push(cField.FIELD);
                      }
                  }
                  this.containedIndexes={};
                  // Obtenemos las definiciones de los modelos, y vemos si tenemos los indices.
                  for(var k in modelFields)
                  {
                      var def=Siviglia.Model.loader.getModelDefinition(k);
                      var indexes=def.INDEXFIELDS;
                      if(modelFields[k].indexOf(indexes[0])>=0)
                      {
                          this.containedIndexes[k]=indexes[0];
                      }
                  }
                  this.wampService=Siviglia.Service.get("wampServer");
                  if(this.wampService)
                  {
                      this.wampService.subscribe('busevent',function(args){
                          if(args[2].appId===this.__identifier)
                          {
                              this.refresh();
                          }

                      }.bind(this))
                  }


              },
              destruct:function()
              {

                  if(this.wampService && this.__busListenerSet)
                  {
                      this.wampService.call("com.adtopy.removeBusListener",[this.__identifier]);
                  }
              },
              methods:{
                  freeze:function()
                  {
                      this.__frozen=true;
                  },
                  unfreeze:function()
                  {
                      this.__frozen=false;
                      return this.refresh();
                  },
                  onResponse:function(response)
                  {
                      this["*data"].apply(response.data,Siviglia.types.BaseType.VALIDATION_MODE_NONE);
                      this["*count"]._setValue(response.count);
                      this.count=response.count;
                      //this.settings.start=response.start;
                      this["*start"]._setValue(response.start);
                      this["*end"]._setValue(response.end);
                      this.rawData = response.data;

                      this.__hasBusListener=false;
                      this.__busListenerSet=false;
                      if(this.wampService)
                      {
                          var currentIds={};
                          for(var k in this.containedIndexes)
                          {
                              currentIds[k]=[];
                              var indexField=this.containedIndexes[k];
                              for(var j=0;j<response.data.length;j++)
                              {
                                  currentIds[k].push(response.data[j][indexField]);
                              }
                              this.wampService.call("com.adtopy.replaceBusListener",[
                                  {channel:'General',path:k,roles:0xFFF,appId:this.__identifier,ids:currentIds,userId:top.Siviglia.config.user.USER_ID}]);
                              this.__busListenerSet=true;
                          }
                      }
                      this.fireEvent("CHANGE",{});
                  },
                  refresh:function(evName)
                  {

                      if(this.__frozen)
                          return;
                      var mName = new Siviglia.Model.ModelDescriptor(this.__model);
                      var location = mName.getDataSourceUrl(this.__dsname, null, this.params,this.settings);
                      if(typeof this.__promises[location]!=="undefined")
                          return this.__promises[location];

                      var transport = new Siviglia.Model.Transport();
                      var m=this;
                      (function(location){
                          var currentPromise=$.Deferred();
                          m.__promises[location]=currentPromise;
                          transport.doGet(location).then(
                            function (response) {
                                if (response.error) {
                                    currentPromise.reject(error);
                                }else {
                                    m.onResponse(response);
                                    currentPromise.resolve();
                                }
                                delete m.__promises[location];
                            },
                            function (error) {
                                currentPromise.reject(error);
                                delete m.__promises[location];
                                throw error;
                            });
                      })(location);
                      return m.__promises[location];
                  },
                  getDynamicParam:function()
                  {
                      for(var k in this.__definition.FIELDS.params.FIELDS)
                      {
                          var cFieldDef=this.__definition.FIELDS.params.FIELDS[k];
                          if(typeof cFieldDef["PARAMTYPE"]!=="undefined" && cFieldDef["PARAMTYPE"]==="DYNAMIC")
                              return k;
                      }
                      return null;
                  },
                  downloadAs:function(mode)
                  {
                      var mName = new Siviglia.Model.ModelDescriptor(this.__model);
                      var location = mName.getDataSourceUrl(this.__dsname, null, this.params,{output:mode});
                      var transport = new Siviglia.Model.Transport();
                      var m=this;
                      var currentPromise=$.Deferred();
                      (function(location){
                          transport.doGet(location).then(
                            function (response) {
                                if (response.error) {
                                    currentPromise.reject(error);
                                }else {
                                    m.onResponse(response);
                                    currentPromise.resolve();
                                }
                            },
                            function (error) {
                                currentPromise.reject(error);
                                throw error;
                            });
                      })(location);
                      return currentPromise;
                  },
                  downloadAsXLSX:function()
                  {
                      this.downloadAs('xlsx');
                  },
                  getFieldsDefinition:function()
                  {
                      return this.meta.FIELDS;
                  },
                  getParamsDefinition:function()
                  {
                      return this.meta.PARAMS;
                  },
                  getRawData: function()
                  {
                      return this.rawData;
                  },
              }
          },

        Instance:
          {
              inherits: 'Siviglia.model.BaseTypedObject',
              construct:function(name,definition)
              {
                  this._name=name;
                  this._descriptor=new Siviglia.Model.ModelDescriptor(name);
                  this.BaseTypedObject(definition);
              },
              methods:
                {
                    getName:function(){
                        return this._name;
                    },
                    getDescriptor:function()
                    {
                        return this._descriptor;
                    },
                    getIndexFields: function () {
                        return this.__definition.INDEXFIELDS;
                    },
                    getDataSource: function (dsName, params) {
                        return Siviglia.Model.loader.getDataSource(this._name,dsName, params);
                    },
                    save: function (instance,actionName) {
                        // Se comprueba que todos los campos requeridos, etc, estan ok.
                        var errors=this.__save();
                        if(errors && errors.length > 0)
                        {
                            console.error("Guardando instancia de "+this._name+" incompleta");
                            console.dir(errors);
                            throw new Exception("Guardando instancia de "+this._name+" incompleta");
                        }
                        var p = $.Deferred();
                        var m = this;
                        var i = this.__getDefinition().INDEXFIELDS;
                        var myIndexes = {};
                        var targetAction = Siviglia.issetOr(actionName,'Edit');


                        var vals=this.getPlainValue();
                        for (var k = 0; k < i.length; k++) {
                            if (!this["*"+i[k]].isEmpty()) {
                                myIndexes[i[k]] = this[i[k]];
                            } else {
                                myIndexes = null;
                                targetAction = 'Add';
                                break;
                            }
                            delete vals[i[k]];
                        }


                        Siviglia.Model.loader.doAction(myIndexes, vals, this._name, targetAction).then(
                          function (r) {
                              if (r.error == 0) {
                                  m.setValue(r.data[0]);
                                  p.resolve(r);
                              } else
                                  p.reject(r);
                          },
                          function (error) {
                              p.reject(error)
                          }
                        )
                        return p;
                    }
                }
          },


        ModelFactory:
          {
              methods:
                {
                    create: function (model) {
                        return Siviglia.Model.loader.getModel(model, null, null);
                    },
                    load: function (model,id, nocache, datasource) {
                        return Siviglia.Model.loader.getModel(model,id)
                    }
                }
          }

    }
});

Siviglia.Model.__instanceCounter=0;
Siviglia.Model.getAppId=function()
{
    Siviglia.Model.__instanceCounter++;
    return Siviglia.Model.__instanceCounter;
}


