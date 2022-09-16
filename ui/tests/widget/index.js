document.getElementsByTagName('title')[0].innerHTML = 'Tests widgets'

// var testPromises = []

testPromises.push(addTestPromise(
  'sivWidget: definition',
  'Un widget se compone de una <b>plantilla HTML</b> y una <b>clase asociada</b>. Estos widgets se invocan mediante una <b>vista</b>.<br>El widget se declara mediante el atributo <b>sivWidget</b>.<br>La clase se declara mediante el atributo <b>widgetCode</b>.<br>La vista invoca un widget dándole al atributo <b>sivView</b> el valor de sivWidget del widget deseado.',
  'widget/definitionTest.html'
))
testPromises.push(addTestPromise(
  'sivWidget: variables de la clase en el widget',
  'Para emplear una variable de la clase en el widget debe declararse con un valor no nulo en preInitialize.<br>Para acceder en el widget al contexto de la clase asociada, y por tanto a sus variables de clase, se emplea el prefijo "<b>*</b>"',
  'widget/classVarTest.html'
))
testPromises.push(addTestPromise(
  'sivWidget: ciclo de vida',
  'El ciclo de vida de un widget es: <u>llamada a preInitialize</u> -> <u>renderizado</u> -> <u>llamada a initialize</u>.<br>En <b>preInitialize</b> aun no se ha renderizado la plantilla, siendo el punto en el que deben declararse las variables usadas en la plantilla.<br>Después de preInitialize y antes de initialize se <b>renderiza la plantilla</b>, bindeando las variables del widget a las variables de la clase.<br>Finalmente, se ejecuta <b>initialize</b>. En ese punto las variables ya están renderizadas por lo que si se cambian en esta fase, la plantilla se renderiza de nuevo automáticamente.<br>En el ejemplo una variable cambia en initialize mediante setInterval.',
  'widget/lifeCycleTest.html'
))
testPromises.push(addTestPromise(
  'sivWidget: acceso al valor de keys en objetos mediante path',
  'Las variables miembro de la clase se pueden navegar como si fueran paths',
  'widget/keyPathTest.html'
))
testPromises.push(addTestPromise(
  'sivWidget: paths dependientes',
  'Los paths pueden depender del valor al que apunten otros paths o del valor de variables.<br>NOTA1: No puede emplearse variables obtenidas mediante composición empleando otras variables: "[%*firstPartVar{%*secondPartVar%}%]"<br>NOTA2: Los paths pueden comenzar opcionalmente por el carácter "/" , pero los paths anidados (que usan {%...%}, en vez de [%...%]) no permiten ese caracter "/" extra.',
  'widget/dependentPathTest.html'
))
testPromises.push(addTestPromise(
  'ParametrizableString (PS): Definición',
  'Se trata de una String en la que se pueden introducir variables que serán resueltas en tiempo de ejecución.<br>Las PS pueden realizar las siguientes operaciones:<br>Sustitución: sustituye el valor indicado entre "[%" y "%]"<br>Sustitución anidada: sustituye primero el valor indicado entre "{%" y "%}" y posteriormente el resultado que haya entre "[%" y "%]"<br>Verificación: se realiza la verificación indicada entre "[%" y "{%" y solo si el resultado es "true" se ejecuta la sustitución indicada entre "{%" y "%}"<br>Transformación: Después de las posibles verificaciones y antes de la sustitución, se toma el valor final a renderizar y se modifica según lo indicado entre "{%" y "%}", separado del nombre de la variable mediante ":"',
  'widget/psDefinitionTest.html'
))
testPromises.push(addTestPromise(
  'Transparencia de nodos',
  'Los nodos html que contienen tags de tipo SivWidget, SivView o SivLoop, no estan incluidos en el DOM final.<br>En el siguiente ejemplo, los nodos que contienen SivWidget,SivView y SivLoop, establecen colores de fondo, que, como se ve, no están en la salida.',
  'widget/nodeTransparencyTest.html'
))
testPromises.push(addTestPromise(
  'Rootnode',
  'Los nodos que componen un widget son accesibles a traves de la propiedad rootNode.<br>RootNode contiene todos los nodos hijos del subwidget renderizado (es un objeto jQuery). Es por ello que no es accesible en preInitialize (aún no se ha renderizado el widget).<br>En este ejemplo, se usa rootNode para encontrar los hijos, dentro del widget actual, que tienen una clase de estilo',
  'widget/rootNodeDefinitionTest.html'
))
testPromises.push(addTestPromise(
  'Promesas en preInitialize',
  'En muchas ocasiones, las variables necesarias para crear un widget, no estan disponibles al llamar a preInitialize. <br>Cuando el contenido de un Widget depende, por ejemplo, de una llamada ajax, y hay que esperar a que ésta llamada termine, desde preInitialize se retorna una promesa.<br>El widget se procesará, cuando la promesa se haya resuelto.<br>En el ejemplo, se utiliza un setTimeout() para simular la llamada ajax.',
  'widget/preinitializePromiseTest.html'
))
testPromises.push(addTestPromise(
  'BaseTypedObject y Widgets',
  'La API de tipos, y los API de widgets, son independientes.Una y otra se pueden usar por separado.<br>Cuando se combinan, desde el punto de vista del Widget, es una variable cualquiera, y se accede de la misma forma.<br>Como cualquier otro BaseTypedObject, hay que llamar a destruct cuando ya no son necesarios.<br>En este ejemplo, se define un BTO en el preInitialize, y se le asigna un valor, usado al renderizar el widget.<br>En el initialize, se modifica el valor del BTO, y, en el destructor del widget, se llama al destructor del BTO.',
  'widget/btoTest.html'
))
testPromises.push(addTestPromise(
  'BTO Remoto',
  'Si la definicion del BTO es remota, es equivalente a cualquier llamada Ajax.<br>Esto significa, retornar una promesa de preInitialize, y resolverla cuando el BTO esté listo.<br>Un BTO remoto puede ser un formulario, o la salida de un datasource.En este caso se utiliza un datasource, y se itera sobre el resultado.<br>La llamada a unfreeze() del datasource, dispara la request y retorna una promesa, que es la que es devuelva en preInitialize',
  'widget/remoteBTOTest.html'
))
testPromises.push(addTestPromise(
  'Datasource',
  'Tratamos de sacar la información de un BTO',
  'widget/dataSourceTest.html'
))
testPromises.push(addTestPromise(
  'Factorias',
  'Este ejemplo utiliza la funcion stringToContextAndObject para crear una pequeña factoria de widgets.<br>Esta factoria simple, utiliza el nombre del tipo del BTO, para instanciar un widget que renderiza ese tipo.<br>Se declara una clase base de renderizado de tipos, de las que deriva una clase para renderizar Strings, otra para renderizar Integer, y en el widget principal, se declara un BTO con esos tipos.<br>Se itera sobre los campos, y,usando sivCall, se crean las instancias de los widgets asociados al tipo.<br>Como nota adicional, los widgets creados por el widget Factoria, se almacenan en una variable, y se destruyen en el destruct(), junto con el bto.<br>',
  'widget/widgetFactoryTest.html'
))
testPromises.push(addTestPromise(
  'Esperando que los subwidgets sean creados',
  'Se crean vistas que dependen de otras vistas, y que se van a resolver en momentos diferentes,<br>La vista raiz debe esperar a que todas las subVistas esten listas, antes de mostrar un mensaje.<br>',
  'widget/waitCompleteTest.html'
))
testPromises.push(addTestPromise(
  'Comunicación entre widgets: eventos',
  'Se muestra la comunicación mediante eventos entre dos widget hijos mediante su padre.<br>',
  'widget/widgetCommunicationTest.html'
))
