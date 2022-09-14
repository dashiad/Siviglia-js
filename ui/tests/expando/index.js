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
  'sivLoop: iteraciones anidadas',
  "Es posible anidar varios sivLoops cuando el elemento extraido del objeto iterable es a su vez otro objeto iterable.<br>" +
  "Para ello solo hay que crear un nuevo sivLoop dentro del primero utilizando la referencia al elemento de la iteración como fuente.<br>",
  'expando/sivLoop.nestedLoopsTest.html'
))
promiseList.push(addTestPromise(
  'sivLoop: refresco ante cambios en los objetos iterados',
  "Cuando el objeto sobre el que se itera cambia, se renderiza de nuevo la plantilla<br>" +
  "Los cambios en el objeto iterado pueden deberse a: nuevos elementos, elementos eliminados o cambios en los valores de los elementos",
  'expando/sivLoop.refreshTest.html'
))
promiseList.push(addTestPromise(
  'sivCall: definition',
  "El atributo sivCall realiza una llamada al metodo especificado mediante su valor, que recibe como parametro el nodo HTML donde se declara.<br>" +
  "Es posible enviar parámetros adicionales mediante el atributo <b>sivParams</b>. El valor de este atributo es un objeto JSON que en el que se pueden emplear referencias a variables de clase y de contexto.<br>" +
  "En este ejemplo se establece el contenido de los nodos usando sivCall en vez de sivValue.<br>" +
  "(Nota: para especificar el JSON dentro del atributo html, se utilizan comillas simples para el valor del atributo y dobles para las clave y valores del JSON, de forma que no hay que escapear las comillas dobles)",
  'expando/sivCall.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivEvent: definition',
  "SivEvent, junto a sivcallback y sivParams, se utiliza para asignar un gestor de eventos.<br>" +
  "Aunque es posible asignar más de 1 evento, el callback y los parámetros son compartidos.<br>" +
  "El nombre de los eventos es el usado por jQuery, y, en caso de especificar más de uno, debe ir separado por comas.<br>",
  'expando/sivEvent.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivIf: definition',
  "SivIf evalua una expresion, y en caso de evaluar a true, renderiza el contenido de su tag." +
  "En este ejemplo, se alterna el valor de una variable, lo que alterna el contenido mostrado.<br>" +
  "la expresion del SivIf se evalua con eval de javascript, por lo que admite los condicionales de javascript",
  'expando/sivIf.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivIf: refresco ante cambios en la condición',
  "Prueba de funcionamiento de la regeneración del contenido de sivIf." +
  "Se prueba cómo sivIf regenera los contenidos a medida que cambia.Especificamente, qué ocurre con los sivId definidos dentro de un sivIf.<br>",
  'expando/sivIf.refreshTest.html'
))
promiseList.push(addTestPromise(
  'sivView: definition',
  "Desde un widget, es posible instanciar otros widgets usando SivView desde dentro de la plantilla.<br>" +
  "La plantilla padre puede pasar parámetros a las vistas hijas, usando sivParams.Estos parámetros se reciben en los métodos preInitialize e <br>" +
  "Los parámetros siguen bindeados, por lo que un cambio en las variables pasadas como parametros, provoca el repintado de las vistas.<br>" +
  "El siguiente ejemplo, pasa 2 variables (una de ellas, un valor fijo, y la otra, una variable bindeada del widget) a la vista hija.Se cambia el valor de la variable, y se refresca la vista hija.<br>" +
  "Primero se define las vistas hijas, y luego la vista padre.<br>",
  'expando/sivView.definitionTest.html'
))