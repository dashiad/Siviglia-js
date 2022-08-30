Siviglia.requireStore = Siviglia.requireStore || {} // Cuando se implemente en Siviglia.js, esta línea va al principio con __store__
Siviglia.requiredPromises = Siviglia.requirePromises || {}

Siviglia.Utils.load=function(assets, isStatic=true, doParse) {
  var loadHTML=function(url,node){
    var promise=$.Deferred();

    $.get(url).then(function(r){
      if(typeof node == "undefined") {
        node=$("<div></div>");
        $(document.body).append(node);
      }
      node.html(r);
      // Ojo, aqui se llama a un objeto Siviglia.App.Page
      promise.resolve(node);
    });
    return promise;
  };
  var loadJS=function(url){
    var promise=$.Deferred();
    var v=document.createElement("script");
    v.onload=function(){
      promise.resolve();
    }
    v.src=url;
    document.head.appendChild(v);
    return promise;
  };
  var loadCSS=function(url){
    var promise=$.Deferred();
    var v=document.createElement("link");
    v.rel="stylesheet";
    v.href=url;
    v.onload=function(){promise.resolve();}
    document.head.appendChild(v);
    return promise;
  };
  var loadWidget = function (config, prevPromise) {
    if (!config.node) {
      config.node = $('<div style="display:none"></div>');
      $(document.body).append(config.node);
    }
    var subdomain = Siviglia.config[isStatic ? 'staticsUrl' : 'baseUrl']
    var promise = $.Deferred();

    var htmlConfig = {
      type: 'html',
      url: subdomain+config.template,
      node: config.node
    }
    var jsConfig = {
      type: 'js',
      url: subdomain+config.js,
    }

    var requirePromise = Siviglia.Utils.load([htmlConfig, jsConfig])
    $.when.apply($, requirePromise).then(function () {
      if (typeof doParse !== "undefined" && doParse === true) {
        var parser = new Siviglia.UI.HTMLParser(config.context, null);
        parser.parse(config.node);
      }
      promise.resolve(config.node);
    })
    return promise
  }

  if (typeof assets === 'string')
    assets = [assets]
  if (typeof assets.type === 'string')
    assets = [assets]

  var mainPromise=$.Deferred();
  var promisesList=[];

  for(var k=0;k<assets.length;k++) {
    var resource=assets[k];
    var resourceObj=null
    if(typeof resource=="string") {
      var type = null
      var splitPath = resource.split('/')
      if(splitPath.length>0) {
        var fileName=splitPath.pop()
        var splitFileName=fileName.split(".");
        if(splitFileName.length > 1)
          type=splitFileName.pop();
        else
          type = 'widget'
      }

      resourceObj= {
        type: type,
        template: resource+'.html',
        js: resource+'.js',
        url: resource,
      }
    } else {
      resourceObj = resource
    }

    switch(resourceObj.type) {
      case "widget":
        promisesList.push(loadWidget(resourceObj))
        break;
      case "html":
        promisesList.push(loadHTML(resourceObj.url));
        break;
      case "js":
        promisesList.push(loadJS(resourceObj.url));
        break;
      case "css":
        promisesList.push(loadCSS(resourceObj.url));
        break;
    }
  }

  $.when.apply($, promisesList).done(function() {
    mainPromise.resolve();
  });
  return mainPromise;
};

Siviglia.require = function (list, isStatics, doParse) {
  function loadJS(url, caller) {
    var promise = $.Deferred();
    var scriptElement = document.createElement("script");
    scriptElement.onload = function () {
      if (Siviglia.requireStore[url] === 0) {
        promise.resolve();
        Siviglia.requireStore[caller] = 1
      }
    }
    scriptElement.src = url;
    document.head.appendChild(scriptElement);
    return promise;
  }


  var requiredURL = list
  var caller = Siviglia.getCaller().pathname

  Siviglia.requireStore[list] = 0
  Siviglia.requireStore[caller] = 1

  // Siviglia.requirePromises[requiredURL] = $.Deferred()
  // loadJS(requiredURL, caller, )

  return Siviglia.Utils.load(list, isStatics, doParse);
}






Siviglia.getCaller = function () {
  var STACK_TRACE_SPLIT_PATTERN = /(?:Error)?\n(?:\s*at\s+)?/;
// For browsers, like Chrome, IE, Edge and more.
  var STACK_TRACE_ROW_PATTERN1 = /^.+?\s\((.+?):\d+:\d+\)$/;
// For browsers, like Firefox, Safari, some variants of Chrome and maybe other browsers.
  var STACK_TRACE_ROW_PATTERN2 = /^(?:.*?@)?(.*?):\d+(?::\d+)?$/;
  var SIVIGLIA_FILE_URL = 'http://statics.adtopy.com/packages/Siviglia/tests/require/require.js' //cambiar cuando se implemente en Siviglia.js

  var stringStack = new Error().stack
  var stack = stringStack.split(STACK_TRACE_SPLIT_PATTERN)
  for (var trace of stack) {
    if (trace !== '' && !trace.includes(SIVIGLIA_FILE_URL)) {
      var [wasteURL, url] = trace.match(STACK_TRACE_ROW_PATTERN1) || trace.match(STACK_TRACE_ROW_PATTERN2) || '';
      break
    }
  }
  if (!url) {
    console.warn("Something went wrong. You should debug it and find out why.");
    return;
  }
  try {
    return new URL(url);
  } catch (e) {
    console.warn(`The URL '${url}' is not valid.`);
  }
}

