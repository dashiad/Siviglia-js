Siviglia.requireStore = Siviglia.requireStore || {} // Cuando se implemente en Siviglia.js, esta línea va al principio con __store__
Siviglia.requiredPromises = Siviglia.requiredPromises || []

Siviglia.Utils.load=function(list, isStatic=true, doParse) {
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

  if (typeof list === 'string')
    list = [list]
  if (typeof list.type === 'string')
    list = [list]

  var mainPromise=$.Deferred();
  var promiseList=[];

  for(var k=0;k<list.length;k++) {
    var resource=list[k];
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
        promiseList.push(loadWidget(resourceObj))
        break;
      case "html":
        promiseList.push(loadHTML(resourceObj.url));
        break;
      case "js":
        promiseList.push(loadJS(resourceObj.url));
        break;
      case "css":
        promiseList.push(loadCSS(resourceObj.url));
        break;
    }
  }

  $.when.apply($, promiseList).done(function() {
    mainPromise.resolve();
  });
  return mainPromise;
};

Siviglia.require = function (list, isStatics, doParse) {
  return Siviglia.Utils.load(list, isStatics, doParse);
}




const STACK_TRACE_SPLIT_PATTERN = /(?:Error)?\n(?:\s*at\s+)?/;
// For browsers, like Chrome, IE, Edge and more.
const STACK_TRACE_ROW_PATTERN1 = /^.+?\s\((.+?):\d+:\d+\)$/;
// For browsers, like Firefox, Safari, some variants of Chrome and maybe other browsers.
const STACK_TRACE_ROW_PATTERN2 = /^(?:.*?@)?(.*?):\d+(?::\d+)?$/;

SIVIGLIA_FILE_URL = 'http://statics.adtopy.com/packages/Siviglia/tests/require/require.js'

Siviglia.getCaller = function () {
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

