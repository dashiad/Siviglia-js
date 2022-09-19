var urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('path')) {
  Siviglia.require(['/packages/Siviglia/ui/tests/' + urlParams.get('path')], true, false)
} else {
  console.log('No se ha indicado un path para el test')
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
    if (JSON.stringify(expected) === JSON.stringify(result)) {
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

  var templateContainer = createHTMLElement('div', 'testDef', codeContainer)
  var templateTitle = createHTMLElement('div', 'resultTitle', templateContainer, 'Template')
  var templateContent = createHTMLElement('div', 'resultCode', templateContainer)
  templateContent.innerHTML = "<pre>" + hljs.highlightAuto(formatHTML(test.template)).value + "</pre>";

  var viewContainer = createHTMLElement('div', 'testView', codeContainer)
  var viewTitle = createHTMLElement('div', 'resultTitle', viewContainer, 'View')
  var viewContent = createHTMLElement('div', 'resultCode', viewContainer)
  viewContent.innerHTML = "<pre>" + hljs.highlightAuto(formatHTML(test.view)).value + "</pre>";

  var classContainer = createHTMLElement('div', 'testCode', codeContainer)
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

  var parser = new Siviglia.UI.HTMLParser();
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

/*// Test extractor
var indexContent = `document.getElementsByTagName('title')[0].innerHTML = 'Tests expandos'\n\nvar promiseList = []\n\n`*/

function checkTests(restart) {

  /*// Test extractor
  saveFileInUserStorage('index.js', indexContent, 'application/javascript')
   */

  if (restart !== false && DEVELOP_MODE !== 0) {
    if (DEVELOP_MODE === -1)
      testStack = [testStack.pop()];
    else
      testStack = [testStack[DEVELOP_MODE - 1]];
  }
  while (testStack.length > 0) {
    var currentTest = testStack.shift();
    try {
      currentTest.cb.apply(null);
      createHTMLTestStructure(currentTest)
    } catch (error) {
      console.log('Falla el test número ' + currentTest.number + ' -> ' + currentTest.name)
      console.dir(error);
    }
  }
}

var testNumber = 0;

function runTest(name, doc, template, view, callback) {

  /*// Test extractor
  var templateContent = '<!-- templateInit -->\n'+template+ '\n<!-- templateEnd -->\n\n'
  var viewContent = '<!-- viewInit -->\n'+view+ '\n<!-- viewEnd -->\n\n'
  var codeContent = '<script>\n  //codeInit\n'+ getStringBetween(callback.toString(2),'{\n  ','}')+'  \n//codeEnd\n</script>'
  var fileContent = templateContent+viewContent+codeContent
  indexContent += `promiseList.push(addTestPromise(
  '${name}',
  '${doc}',
  'path unsetted'
))\n`
  saveFileInUserStorage(name+'.html', fileContent, 'text/html')*/

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

function saveFileInUserStorage(filename, data, type) {
  const file = new Blob([data], { type: type })
  const a = document.createElement('a')
  const url = URL.createObjectURL(file)
  a.href = url
  a.download = filename
  document.body.appendChild(a)
  a.click()
  setTimeout(function() {
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }, 0)
}


function addTestPromise(name, doc, path) {
  var template
  var view
  var code
  var promise = $.Deferred();
  /* Parece que no hace falta incluir el path completo del test
  *  Se deja el código por si en un futuro se quiere cambiar
  * */
  /*var uiTestPath = '/packages/Siviglia/ui/tests/'
  var url = Siviglia.config.staticsUrl + uiTestPath + path
  $.get(url).then(function(res) {*/
  $.get(path).then(function (res) {
    template = getStringBetween(res, '<!-- templateInit -->', '<!-- templateEnd -->').replace(/(\r\n|\n|\r)/gm, "");
    view = getStringBetween(res, '<!-- viewInit -->', '<!-- viewEnd -->').replace(/(\r\n|\n|\r)/gm, "");
    code = Function(getStringBetween(res, '//codeInit', '//codeEnd'))
    /* ToDo: al estar dentro de la resolución de la promesa runTest (que es quien mete en el array el test) no se
    *   asegura que el orden te los test sea siempre el mismo; depende del orden en el que se resuelvan los $.get */
    runTest(name, doc, template, view, code)
    promise.resolve()
  })

  return promise
}

function getStringBetween(str, start, end) {
  const result = str.match(new RegExp(start + "(.*)" + end, 's'));

  return result[1];
}