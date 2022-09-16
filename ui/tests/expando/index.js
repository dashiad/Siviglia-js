document.getElementsByTagName('title')[0].innerHTML = 'Tests expandos'

var promiseList = []

promiseList.push(addTestPromise(
  'sivValue: definición',
  'Por defecto, sivValue establece el atributo innerHTML del nodo HTML donde esté.<br>También es posible establecer otras parejas atributo-valor, separando cada pareja mediante el símbolo "<b>::</b>", y el nombre y el valor de cada atributo por "<b>|</b>".',
  'expando/sivValue.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivValue: valor como parametrizableString',
  'El valor de sivValue es una parametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable: puede referenciar más de una, texto o expresiones complejas.',
  'expando/sivValue.psValueTest.html'
))
promiseList.push(addTestPromise(
  'sivLoop: definición',
  'Crea una plantilla para cada elemento de un objeto iterable.<br>Se emplea como base para generar las plantillas de cada iteración al conjunto de nodo HTML que sean hijos del nodo HTML en el que se define el atributo sipLoop.<br>En cada iteracion el elemento correspondiente del objeto iterable es accesible mediante el valor de <b>contextIndex</b> con el prefijo "<b>@</b>".<br>Ademas de esta variable de contexto, que apunta a los valores, tambien define una que apunta a la key, la cual es accesible con el valor de <b>contextIndex</b>, el plefijo "<b>@</b>" y el sufijo "<b>-index</b>".',
  'expando/sivLoop.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivLoop: iteraciones anidadas',
  'Es posible anidar varios sivLoops cuando el elemento extraido del objeto iterable es a su vez otro objeto iterable.<br>Para ello solo hay que crear un nuevo sivLoop dentro del primero utilizando la referencia al elemento de la iteración como fuente.<br>',
  'expando/sivLoop.nestedLoopsTest.html'
))
promiseList.push(addTestPromise(
  'sivLoop: refresco ante cambios en los objetos iterados',
  'Cuando el objeto sobre el que se itera cambia, se renderiza de nuevo la plantilla<br>Los cambios en el objeto iterado pueden deberse a: nuevos elementos, elementos eliminados o cambios en los valores de los elementos',
  'expando/sivLoop.refreshTest.html'
))
promiseList.push(addTestPromise(
  'sivCall: definition',
  'El atributo sivCall realiza una llamada al metodo especificado mediante su valor, que recibe como parametro el nodo HTML donde se declara.<br>Es posible enviar parámetros adicionales mediante el atributo <b>sivParams</b>. El valor de este atributo es un objeto JSON que en el que se pueden emplear referencias a variables de clase y de contexto.<br>En este ejemplo se establece el contenido de los nodos usando sivCall en vez de sivValue.<br>(Nota: para especificar el JSON dentro del atributo html, se utilizan comillas simples para el valor del atributo y dobles para las clave y valores del JSON, de forma que no hay que escapear las comillas dobles)',
  'expando/sivCall.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivEvent: definition',
  'SivEvent, junto a sivcallback y sivParams, se utiliza para asignar un gestor de eventos.<br>Aunque es posible asignar más de 1 evento, el callback y los parámetros son compartidos.<br>El nombre de los eventos es el usado por jQuery, y, en caso de especificar más de uno, debe ir separado por comas.<br>',
  'expando/sivEvent.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivIf: definition',
  'SivIf evalua una expresion, y en caso de evaluar a true, renderiza el contenido de su tag.En este ejemplo, se alterna el valor de una variable, lo que alterna el contenido mostrado.<br>la expresion del SivIf se evalua con eval de javascript, por lo que admite los condicionales de javascript',
  'expando/sivIf.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivIf: refresco ante cambios en la condición',
  'Prueba de funcionamiento de la regeneración del contenido de sivIf.Se prueba cómo sivIf regenera los contenidos a medida que cambia.Especificamente, qué ocurre con los sivId definidos dentro de un sivIf.<br>',
  'expando/sivIf.refreshTest.html'
))
promiseList.push(addTestPromise(
  'sivView: definition',
  'Desde un widget, es posible instanciar otros widgets usando SivView desde dentro de la plantilla.<br>La plantilla padre puede pasar parámetros a las vistas hijas, usando sivParams.Estos parámetros se reciben en los métodos preInitialize e <br>Los parámetros siguen bindeados, por lo que un cambio en las variables pasadas como parametros, provoca el repintado de las vistas.<br>El siguiente ejemplo, pasa 2 variables (una de ellas, un valor fijo, y la otra, una variable bindeada del widget) a la vista hija.Se cambia el valor de la variable, y se refresca la vista hija.<br>Primero se define las vistas hijas, y luego la vista padre.<br>',
  'expando/sivView.definitionTest.html'
))
promiseList.push(addTestPromise(
  'SivId y ByCode: definition',
  'Los nodos que contienen el atributo data-sivId, se mapean a variables con el mismo nombre en la clase del widget <br>Todos los ejemplos hasta ahora, han instanciado los widgets desde HTML, con un tag sivView. En este ejemplo, se instancia una vista a traves de código.<br>Los parámetros recibidos son: 1)Nombre de la template, 2)Parametros (recibidos en preInitialize), 3)Bloques (actualmente sin uso), 4)Placeholder (establer a un div vacio), 5)instancia de Siviglia.Path.ContextStack<br>En el ejemplo, se crea una instancia del widget Test.Sample, dentro del nodo identificado por sivId=here<br>Una vez creada la instancia, se llama a su metodo __build, que devuelve una promesa.El widget estará construido cuando la promesa se resuelva<br>Un punto importante, es que los widgets creados desde código, deben ser destruidos cuando no son necesarios (en este caso, se hace en el destruct del propio widget)',
  'expando/sivId-byCode.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivLayout: definition',
  'En este ejemplo se especifican varios layouts, todos apuntando al mismo data-widgetCode.<br>Cuando se instancia una vista de un widget es posible especificar un layout alternativo con el atributo data-sivLayout.<br>Esta característica es uno de los pilares del sistema de widgets. Significa una separación entre la gestión del código de un widget, y el html que lo renderiza.<br>El separar las clases de los layouts, permite que haya varias formas de mostrar un mismo elemento conceptual.<br>En este ejemplo, se utiliza un mismo widget, simulando un menu, que se muestra usando varios layouts diferentes.',
  'expando/sivLayout.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivViewName: definition',
  'Usando data-viewName, es posible mapear widgets en el widget padre.<br>El valor de esta propiedad es una parametrizable string.<br>data-sivName es la forma de invocar el código de un widget hijo en el código del widget padre.<br>No debe ser empleado en preInitialize porque el ese punto aún no existe la relación.',
  'expando/sivViewName.definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivPromise: definition',
  'El expando Promise, ejecuta sus contenidos cuando una promesa se resuelve',
  'expando/sivPromise.definitionTest.html'
))
