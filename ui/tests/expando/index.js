document.getElementsByTagName('title')[0].innerHTML = 'Tests expandos'

var promiseList = []

promiseList.push(addTestPromise(
  'sivValue: definición',
  "Por defecto, sivValue establece el atributo innerHTML del nodo HTML donde esté.<br>" +
  "También es posible establecer otras parejas atributo-valor, separando cada pareja mediante el símbolo \"<b>::</b>\", y el nombre y el valor de cada atributo por \"<b>|</b>\".",
  'expando/sivValue.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivValue: valor como parametrizableString',
  "El valor de sivValue es una parametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable: puede referenciar más de una, texto o expresiones complejas.",
  'expando/sivValue.psValueTest.html'
))
promiseList.push(addTestPromise(
  'sivLoop: definición',
  "Crea una plantilla para cada elemento de un objeto iterable.<br>" +
  "Se emplea como base para generar las plantillas de cada iteración al conjunto de nodo HTML que sean hijos del nodo HTML en el que se define el atributo sipLoop.<br>" +
  "En cada iteracion el elemento correspondiente del objeto iterable es accesible mediante el valor de <b>contextIndex</b> con el prefijo \"<b>@</b>\".<br>" +
  "Ademas de esta variable de contexto, que apunta a los valores, tambien define una que apunta a la key, la cual es accesible con el valor de <b>contextIndex</b>, el plefijo \"<b>@</b>\" y el sufijo \"<b>-index</b>\".",
  'expando/sivLoop.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivValue: valor como parametrizableString',
  "El valor de sivValue es una parametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable: puede referenciar más de una, texto o expresiones complejas.",
  'expando/sivValue.psValueTest.html'
))