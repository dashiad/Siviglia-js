document.getElementsByTagName('title')[0].innerHTML = 'Tests expandos'

var promiseList = []

promiseList.push(addTestPromise(
  "sivValue: definición",
  "Por defecto, sivValue establece el atributo innerHTML del nodo HTML donde esté.<br>" +
  "También es posible establecer otras parejas atributo-valor, separando cada pareja mediante el símbolo \"<b>::</b>\", y el nombre y el valor de cada atributo por \"<b>|</b>\".",
  'expando/sivValue.definitionTest.html'
))
promiseList.push(addTestPromise(
  "Nombre de test 2",
  "documentación 2",
  'testTemplate.html'
))