const urlParams = new URLSearchParams(window.location.search);
const TEST_DESTROY = urlParams.get('test') || true
const TEST_FOLDER_PATH = '/packages/Siviglia/ui/tests/'

let testPath
let isSingleTest = false
let pathParameter
if (urlParams.has('path')) {
  pathParameter = urlParams.get('path')
  testPath = TEST_FOLDER_PATH + pathParameter
  const splitPath = pathParameter.split('/')
  const lastPathElement = splitPath.at(-1)
  if (hasKnownExtension(lastPathElement)) {
    isSingleTest = true
  }
}
var testStack = [];

function hasKnownExtension(str) {
  const knownExtensions = ['js', 'css', 'html', 'php']
  if (str.includes('.')) {
    for (const extension of knownExtensions) {
      if (str.split('.').pop() === extension) {
        return true
      }
    }
  }
  return false
}

hljs.initHighlightingOnLoad();


function getStringBetween(str, start, end) {
  const result = str.match(new RegExp(start + "(.*)" + end, 's'));

  return result[1];
}

function fetchTestContent(path, index) {
  var testPromise = $.Deferred();
  /* Parece que no hace falta incluir el path completo del test
  *  Se deja el código por si en un futuro se quiere cambiar
  * */
  /*var uiTestPath = '/packages/Siviglia/ui/tests/'
  var url = Siviglia.config.staticsUrl + uiTestPath + path
  $.get(url).then(function(res) {*/
  $.get(path).then(function (res) {
    testStack[index].template = getStringBetween(res, '<!-- templateInit -->', '<!-- templateEnd -->').replace(/(\r\n|\n|\r)/gm, "");
    testStack[index].view = getStringBetween(res, '<!-- viewInit -->', '<!-- viewEnd -->').replace(/(\r\n|\n|\r)/gm, "");
    testStack[index].callback = Function(getStringBetween(res, '//codeInit', '//codeEnd'))
    testPromise.resolve()
  })

  return testPromise
}

function createTestStack(listToBuild) {
  const stackPromise = $.Deferred()
  const testPromises = []
  let testIndex = 0
  for (const test of listToBuild) {
    testStack.push({
      number: testIndex + 1,
      name: test.name,
      doc: test.doc,
    })
    testPromises.push(fetchTestContent(test.path, testIndex))

    testIndex++
  }

  $.when.apply($, testPromises).done(function () {
    stackPromise.resolve()
  })

  return stackPromise
}

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
  classContent.innerHTML = "<pre>" + hljs.highlightAuto(test.callback.toString()).value + "</pre>";

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

  if (TEST_DESTROY === true && isSingleTest) {
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

function buildTests() {
  while (testStack.length > 0) {
    var currentTest = testStack.shift();
    try {
      currentTest.callback.apply(null);
      createHTMLTestStructure(currentTest)
    } catch (error) {
      console.log('Falla el test número ' + currentTest.number + ' -> ' + currentTest.name)
      console.dir(error);
    }
  }
}

Siviglia.require(['dependencies/js/testsList.js'], true, false).then(function () {
  let selectedTests = []
  if (isSingleTest) {
    selectedTests.push(testsList.find(function (test) {
      return test.path === pathParameter
    }))
  } else if (urlParams.has('path')) {
    for (const test of testsList) {
      if (test.path.includes(pathParameter)) {
        selectedTests.push(test)
      }
    }
  } else {
    selectedTests = testsList
  }
    createTestStack(selectedTests).then(function () {
      buildTests()
    })
  }
)