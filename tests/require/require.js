Siviglia.requireStore = Siviglia.requireStore || {} // Cuando se implemente en Siviglia.js, esta línea va al principio con __store__
Siviglia.requiredPromises = Siviglia.requiredPromises || []

Siviglia.Utils.load=function(assets, isStatic, doParse) {
  var loadHTML=function(url,node,prevPromise){
    var resourcePromise=$.Deferred();
    $.get(url).then(function (r) {
      var add = function () {
        if (typeof node == "undefined" || node == null) {
          node = $("<div></div>");
          $(document.body).append(node);
        }
        node.html(r);
        // Ojo, aqui se llama a un objeto Siviglia.App.Page
        resourcePromise.resolve(node);
      }

      if (typeof prevPromise == "undefined" || prevPromise == null)
        add();
      else
        prevPromise.then(function () {add();});
    });
    return resourcePromise;
  };
  var loadJS = function (url, prevPromise) {
    var resourcePromise = $.Deferred();
    var add = function () {
      var v = document.createElement("script");
      v.onload = function () {
        resourcePromise.resolve();
      }
      v.src = url;
      document.head.appendChild(v);
    }
    if (typeof prevPromise == "undefined" || prevPromise == null)
      add();
    else
      prevPromise.then(function () {
        add();});
    return resourcePromise;
  };
  var loadCSS=function(url){
    var resourcePromise=$.Deferred();
    var v=document.createElement("link");
    v.rel="stylesheet";
    v.href=url;
    v.onload=function(){resourcePromise.resolve();}
    document.head.appendChild(v);
    return resourcePromise;
  };

  var subpromises=[];
  var loadWidget = function (config, prevPromise) {
    if (!config.node) {
      config.node = $('<div style="display:none"></div>');
      $(document.body).append(config.node);
    }
    var widgetURL = Siviglia.config[isStatic ? 'staticsUrl' : 'baseUrl']
    var widgetPromise = $.Deferred();
    subpromises.push(widgetPromise);
    var widgetSubPromises = [];

    var htmlPromise = loadHTML(widgetURL + config.template, config.node, prevPromise)
    widgetSubPromises.push(htmlPromise);

    var jsPromise = loadJS(widgetURL + config.js, htmlPromise)
    widgetSubPromises.push(jsPromise);

    $.when.apply($, widgetSubPromises).then(function () {
      if (typeof doParse !== "undefined" && doParse === true) {
        var parser = new Siviglia.UI.HTMLParser(config.context, null);
        parser.parse(config.node);
      }
      widgetPromise.resolve(config.node);
    })
    return jsPromise
  }



  var lastPromise=null;
  for(var k=0;k<assets.length;k++)
  {
    var p=assets[k];

    if(typeof p=="string")
    {
      var type="html";
      // Es una simple cadena.Se busca que tipo de recurso puede ser.
      var aa=document.createElement("a");
      aa.href=p;
      var path=aa.pathname.split("/");
      if(path.length>0)
      {
        var ss=path[path.length-1];
        var suffix=ss.split(".");
        if(suffix.length > 1)
          type=suffix.pop();
        else
          type="widget";
      }
      switch(type) {
        case "html": {
          lastPromise=loadHTML(p,null,lastPromise)
        }break;
        case "js": {
          lastPromise=loadJS(p,lastPromise);
        }break;
        case "css": {
          subpromises.push(loadCSS(p));
        }break;
        case "widget":{
          lastPromise=loadWidget({"template":p+".html","js":p+".js"},lastPromise)
        }
      }

    } else {
      lastPromise = loadWidget(p,lastPromise)
    }
  }

  var curPromise=$.Deferred();
  $.when.apply($, subpromises).done(function() {

    curPromise.resolve();
  });
  return curPromise;
};
Siviglia.require = function (list, isStatics, doParse) {
  var fileParams = getFileParams()
  console.log(fileParams)
  Siviglia.requireStore[fileParams] = 1


  return Siviglia.Utils.load(list, isStatics, doParse);
}




const STACK_TRACE_SPLIT_PATTERN = /(?:Error)?\n(?:\s*at\s+)?/;
// For browsers, like Chrome, IE, Edge and more.
const STACK_TRACE_ROW_PATTERN1 = /^.+?\s\((.+?):\d+:\d+\)$/;
// For browsers, like Firefox, Safari, some variants of Chrome and maybe other browsers.
const STACK_TRACE_ROW_PATTERN2 = /^(?:.*?@)?(.*?):\d+(?::\d+)?$/;

SIVIGLIA_FILE_URL = 'http://statics.adtopy.com/packages/Siviglia/tests/require/require.js'

function getFileParams () {
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
    const urlObj = new URL(url);
    return urlObj.pathname; // This feature doesn't exists in IE, in this case you should use urlObj.search and handle the query parsing by yourself.
  } catch (e) {
    console.warn(`The URL '${url}' is not valid.`);
  }
}

