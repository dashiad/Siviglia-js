var urlParams = new URLSearchParams(window.location.search);
var DEVELOP_MODE;
if (!urlParams.has("test")) {
  //DEVELOP_MODE=47;    // Specific test number
  //DEVELOP_MODE=0;     // All tests
  //DEVELOP_MODE=(-1);  // Latest test
  DEVELOP_MODE=(-1);
} else {
  DEVELOP_MODE = urlParams.get("test");
}
var TEST_DESTROY=true;
var Siviglia=Siviglia || {};
Siviglia.config={
  baseUrl:'http://reflection.adtopy.com/',
  staticsUrl:'http://statics.adtopy.com/',
  metadataUrl:'http://metadata.adtopy.com/',
  user:{
    USER_ID:"1",
    TOKEN:"1"
  },
  wampServer:{
    "URL":"ws://statics.adtopy.com",
    "PORT":"8999",
    "REALM":"adtopy",
    /*"user":{
      "USER_ID": "1",
      "TOKEN":'1',
    },*/
  },
  // Si el mapper es XXX, debe haber:
  // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
  // 2) Un Mapper en Siviglia.Model.XXXMapper
  // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
  mapper:'Siviglia'
};
Siviglia.Model.initialize(Siviglia.config);
  var wConfig=Siviglia.config.wampServer;
  var wampServer = new Siviglia.comm.WampServer(
  wConfig.URL,
  wConfig.PORT,
  wConfig.REALM,
  top.Siviglia.config.user.TOKEN
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

  function showResult(name,doc,template,view,result,callback,testNumber,exception)
  {
    var className = "result resultError";
    var message = "ERROR";
    if(!exception)
  {
    if (JSON.stringify(expected) == JSON.stringify(result)) {
    className = "result resultOk";
    message = "OK!";
  }
  }
    else
    message="ERROR:"+exception;
  }
  var cbStack=[];
  Siviglia.debug=true;
  var formatHTML = function(code, stripWhiteSpaces, stripEmptyLines) {
  "use strict";
  var whitespace          = ' '.repeat(4);             // Default indenting 4 whitespaces
  var currentIndent       = 0;
  var char                = null;
  var nextChar            = null;


  var result = '';
  for(var pos=0; pos <= code.length; pos++) {
  char            = code.substr(pos, 1);
  nextChar        = code.substr(pos+1, 1);

  // If opening tag, add newline character and indention
  if(char === '<' && nextChar !== '/') {
  result += '\n' + whitespace.repeat(currentIndent);
  currentIndent++;
}
  // if Closing tag, add newline and indention
  else if(char === '<' && nextChar === '/') {
  // If there're more closing tags than opening
  if(--currentIndent < 0) currentIndent = 0;
  result += '\n' + whitespace.repeat(currentIndent);
}

  // remove multiple whitespaces
  else if(stripWhiteSpaces === true && char === ' ' && nextChar === ' ') char = '';
  // remove empty lines
  else if(stripEmptyLines === true && char === '\n' ) {
  if(code.substr(pos, code.substr(pos).indexOf("<")).trim() === '' ) char = '';
}

  result += char;
}

  return result;
}

  function checkTests(restart) {
  if(restart!==false) {
  if (DEVELOP_MODE !== 0) {
  if (DEVELOP_MODE === -1)
  cbStack = [cbStack.pop()];
  else
  cbStack = [cbStack[DEVELOP_MODE-1]];
}
}
  var parser = new Siviglia.UI.HTMLParser();
  while(cbStack.length>0)
{
  var cItem=cbStack.shift();
  try {
  var rs = cItem.cb.apply(null);

  var nDiv = document.createElement("div");
  nDiv.className="testDiv ";
  var divTitle=document.createElement("div");
  nDiv.appendChild(divTitle);
  divTitle.innerHTML = "Test " + cItem.number + " (" + cItem.name + ") <i style='font-size:10px'>[listeners:"+countListeners()+"]</i>";
  divTitle.className="testTitle";
  var divDoc=document.createElement("div")
  divDoc.innerHTML=cItem.doc;
  divDoc.className="testDoc";
  nDiv.appendChild(divDoc);
  var divCont=document.createElement("div");
  divCont.className="testCont";
  // divCont.style.height="250px";
  nDiv.appendChild(divCont);

  var divDef=document.createElement("div");
  divDef.style.overflowY="scroll";
  divDef.className="testDef";
  var cTitle=document.createElement("div");
  cTitle.className="resultTitle";
  cTitle.innerHTML="Templates";
  divDef.appendChild(cTitle);
  var cContent=document.createElement("div");
  cContent.className="resultCode";
  cContent.innerHTML="<pre>"+hljs.highlightAuto(formatHTML(cItem.template)).value+"</pre>";
  divDef.appendChild(cContent);
  divCont.appendChild(divDef);

  divDef=document.createElement("div");
  divDef.style.overflowY="scroll";
  divDef.className="testView";
  cTitle=document.createElement("div");
  cTitle.className="resultTitle";
  cTitle.innerHTML="View";
  divDef.appendChild(cTitle);
  cContent=document.createElement("div");
  cContent.className="resultCode";
  cContent.innerHTML="<pre>"+hljs.highlightAuto(formatHTML(cItem.view)).value+"</pre>";
  divDef.appendChild(cContent);
  divCont.appendChild(divDef);

  divDef=document.createElement("div");
  divDef.style.overflowY="scroll";
  divDef.className="testCode";
  cTitle=document.createElement("div");
  cTitle.className="resultTitle";
  cTitle.innerHTML="Code";
  divDef.appendChild(cTitle);
  cContent=document.createElement("div");
  cContent.className="resultCode";
  cContent.innerHTML="<pre>"+hljs.highlightAuto(cItem.cb.toString()).value+"</pre>";
  divDef.appendChild(cContent);
  divCont.appendChild(divDef);


  divClear=document.createElement("div");
  divClear.style.clear="both";
  nDiv.appendChild(divClear);
  divResult=document.createElement("div");
  divResult.className="testResult";

  cTitle=document.createElement("div");
  cTitle.className="resultTitle";
  cTitle.innerHTML="Result";
  divResult.appendChild(cTitle);
  var cRes=document.createElement("div");
  cRes.id="testCont"+cItem.number;
  cRes.className="resultContent";
  cRes.innerHTML='<div style="display:none">'+cItem.template+'</div>'+cItem.view;
  divResult.appendChild(cRes);
  nDiv.appendChild(divResult);
  document.body.appendChild(nDiv);
  parser.parse($("#testCont"+cItem.number));
  if(TEST_DESTROY===true && DEVELOP_MODE!==0)
{
  var onClicked=function(){
  var nListeners=countListeners();
  var nManagers=countManagers();
  var nDiv=document.createElement("div");
  nDiv.innerHTML="Listeners:<b>"+nListeners+"</b> Managers:<b>"+nManagers+"</b>";
  parser.destruct();
  nListeners=countListeners();
  nManagers=countManagers();

  nDiv.innerHTML+="<br>Despues:<br>Listeners:<b>"+nListeners+"</b> Managers:<b>"+nManagers+"</b>";
  document.body.prependChild(nDiv);
  if(nManagers > 0)
{
  for(var k in Siviglia.Dom.existingManagers)
{
  console.log("MANAGERS:");
  console.dir(Siviglia.Dom.existingManagers[k]);
}
}
  if(nListeners>0)
{
  for(var k in Siviglia.Dom.existingListeners)
{
  console.log("LISTENERS:");
  console.dir(Siviglia.Dom.existingListeners[k]);
}
}
}
  var button=document.createElement("button");
  button.onclick=onClicked;
  button.innerHTML="Destroy";
  document.body.prependChild(button);

}
} catch (e) {
  console.dir(e);
}
}
}
  var testNumber=0;
  function runTest(name,doc,template,view,callback) {
  testNumber++;
  if(typeof expectedResult=="undefined")
  expectedResult=true;

  cbStack.push({name:name,
  doc:doc,
  template:template,
  view:view,
  cb:callback,number:testNumber});
}