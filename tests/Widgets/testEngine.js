var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('name')) {
  var testLoader = document.createElement('script')
  testLoader.src = 'http://statics.adtopy.com/packages/Siviglia/tests/widgets/' + urlParams.get('name') + '.js'
  document.body.appendChild(testLoader)
}
var DEVELOP_MODE;
if (!urlParams.has("test")) {
  //DEVELOP_MODE=47;    // Specific test number
  //DEVELOP_MODE=0;     // All tests
  //DEVELOP_MODE=(-1);  // Latest test
  DEVELOP_MODE = (-1);
} else {
  DEVELOP_MODE = parseInt(urlParams.get("test"));
}
var TEST_DESTROY = true;

var Siviglia = Siviglia || {};
Siviglia.debug = true;

Siviglia.config = {
  baseUrl: 'http://reflection.adtopy.com/',
  staticsUrl: 'http://statics.adtopy.com/',
  metadataUrl: 'http://metadata.adtopy.com/',
  user: {
    USER_ID: "1",
    TOKEN: "1"
  },
  wampServer: {
    "URL": "ws://statics.adtopy.com",
    "PORT": "8999",
    "REALM": "adtopy",
  },
  // Si el mapper es XXX, debe haber:
  // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
  // 2) Un Mapper en Siviglia.Model.XXXMapper
  // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
  mapper: 'Siviglia'
};
Siviglia.Model.initialize(Siviglia.config);

var wConfig = Siviglia.config.wampServer;
var wampServer = new Siviglia.comm.WampServer(
  wConfig.URL,
  wConfig.PORT,
  wConfig.REALM,
  Siviglia.config.user.TOKEN
);
Siviglia.Service.add("wampServer", wampServer);

var parser = new Siviglia.UI.HTMLParser();
parser.parse($(document.body));
hljs.initHighlightingOnLoad();


function countListeners() {
  var s = 0;
  for (var k in Siviglia.Dom.existingListeners)
    s++;
  return s;
}

function countManagers() {
  var s = 0;
  for (var k in Siviglia.Dom.existingManagers)
    s++;
  return s;
}

function showResult(name, doc, template, view, result, callback, testNumber, exception) {
  var className = "result resultError";
  var message = "ERROR";
  if (!exception) {
    if (JSON.stringify(expected) == JSON.stringify(result)) {
      className = "result resultOk";
      message = "OK!";
    }
  } else
    message = "ERROR:" + exception;
}

var formatHTML = function (code, stripWhiteSpaces, stripEmptyLines) {
  "use strict";
  var whitespace = ' '.repeat(2);  // Default indenting 4 whitespaces
  var currentIndent = 0;
  var char = null;
  var nextChar = null;

  var result = '';
  for (var pos = 0; pos <= code.length; pos++) {
    char = code.substring(pos, pos + 1);
    nextChar = code.substring(pos + 1, pos + 2);

    // If opening tag, add newline character and indention
    if (char === '<' && nextChar !== '/') {
      result += '\n' + whitespace.repeat(currentIndent);
      currentIndent++;
    }
    // if Closing tag, add newline and indention
    else if (char === '<' && nextChar === '/') {
      // If there're more closing tags than opening
      if (--currentIndent < 0) currentIndent = 0;
      result += '\n' + whitespace.repeat(currentIndent);
    }

    // remove multiple whitespaces
    else if (stripWhiteSpaces === true && char === ' ' && nextChar === ' ') char = '';
    // remove empty lines
    else if (stripEmptyLines === true && char === '\n') {
      if (code.substr(pos, code.substr(pos).indexOf("<")).trim() === '') char = '';
    }

    result += char;
  }

  return result;
}

function createHTMLElement(tagName, className, parent, content) {
  var htmlElement = document.createElement(tagName)
  if (className)
    htmlElement.className = className
  if (content)
    htmlElement.innerHTML = content
  if (parent)
    parent.appendChild(htmlElement)

  return htmlElement
}

function createHTMLTestStructure(test) {
  var testContainer = createHTMLElement('div', 'testDiv', document.body)
  var titleDiv = createHTMLElement('div', 'testTitle', testContainer)
  titleDiv.innerHTML = "Test " + test.number + " (" + test.name + ") <i style='font-size:10px'>[listeners:" + countListeners() + "]</i>";
  var descriptionDiv = createHTMLElement('div', 'testDoc', testContainer, test.doc)

  var codeContainer = createHTMLElement('div', 'testCont', testContainer)
  codeContainer.style.height="250px";

  var templateContainer = createHTMLElement('div', 'testDef', codeContainer)
  templateContainer.style.overflowY = "scroll";
  var templateTitle = createHTMLElement('div', 'resultTitle', templateContainer, 'Template')
  var templateContent = createHTMLElement('div', 'resultCode', templateContainer)
  templateContent.innerHTML = "<pre>" + hljs.highlightAuto(formatHTML(test.template)).value + "</pre>";

  var viewContainer = createHTMLElement('div', 'testView', codeContainer)
  viewContainer.style.overflowY = "scroll";
  var viewTitle = createHTMLElement('div', 'resultTitle', viewContainer, 'View')
  var viewContent = createHTMLElement('div', 'resultCode', viewContainer)
  viewContent.innerHTML = "<pre>" + hljs.highlightAuto(formatHTML(test.view)).value + "</pre>";

  var classContainer = createHTMLElement('div', 'testCode', codeContainer)
  classContainer.style.overflowY = "scroll";
  var classTitle = createHTMLElement('div', 'resultTitle', classContainer, 'Code')
  var classContent = createHTMLElement('div', 'resultCode', classContainer)
  classContent.innerHTML = "<pre>" + hljs.highlightAuto(test.cb.toString()).value + "</pre>";

  var divClear = createHTMLElement('div', null, testContainer)
  divClear.style.clear = "both";

  var resultContainer = createHTMLElement('div', 'testResult', testContainer)
  resultContainer.className = "testResult";
  var resultTitle = createHTMLElement('div', 'resultTitle', resultContainer, 'Result')
  var resultContent = createHTMLElement('div', 'resultContent', resultContainer)
  resultContent.id = "testCont" + test.number;
  resultContent.innerHTML = '<div style="display:none">' + test.template + '</div>' + test.view;

  parser.parse($("#testCont" + test.number));

  if (TEST_DESTROY === true && DEVELOP_MODE !== 0) {
    var onClicked = function () {
      var nListeners = countListeners();
      var nManagers = countManagers();
      var nDiv = document.createElement("div");
      nDiv.innerHTML = "Listeners:<b>" + nListeners + "</b> Managers:<b>" + nManagers + "</b>";
      parser.destruct();
      nListeners = countListeners();
      nManagers = countManagers();

      nDiv.innerHTML += "<br>Despues:<br>Listeners:<b>" + nListeners + "</b> Managers:<b>" + nManagers + "</b>";
      document.body.prependChild(nDiv);
      if (nManagers > 0) {
        for (var k in Siviglia.Dom.existingManagers) {
          console.log("MANAGERS:");
          console.dir(Siviglia.Dom.existingManagers[k]);
        }
      }
      if (nListeners > 0) {
        for (var k in Siviglia.Dom.existingListeners) {
          console.log("LISTENERS:");
          console.dir(Siviglia.Dom.existingListeners[k]);
        }
      }
    }
    var destructionButton = createHTMLElement('button', null, null, 'Destroy')
    destructionButton.onclick = onClicked;
    document.body.prependChild(destructionButton);
  }
}

var testStack = [];
function checkTests(restart) {
  if (restart !== false && DEVELOP_MODE !== 0) {
    if (DEVELOP_MODE === -1)
      testStack = [testStack.pop()];
    else
      testStack = [testStack[DEVELOP_MODE - 1]];
  }
  // var parser = new Siviglia.UI.HTMLParser();
  while (testStack.length > 0) {
    var currentTest = testStack.shift();
    try {
      currentTest.cb.apply(null);
      createHTMLTestStructure(currentTest)
    } catch (error) {
      console.dir(error);
    }
  }
}

var testNumber = 0;
function runTest(name, doc, template, view, callback) {
  testNumber++;
  if (typeof expectedResult == "undefined")
    expectedResult = true;

  testStack.push({
    name: name,
    doc: doc,
    template: template,
    view: view,
    cb: callback,
    number: testNumber
  });
}