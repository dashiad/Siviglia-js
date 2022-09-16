document.getElementsByTagName('title')[0].innerHTML = 'All Tests'

var promiseList = []

// Expando
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


// Input
promiseList.push(addTestPromise(
  'Input: definición',
  'Se trata de containers que contienen todo lo relacionado con un campo de entrada de información del usuario en el UI.<br>Para generar un input se emplea una vista del widget <b>StdInputContainer</b>, al cual se asocia un campo del formulario al que pertenece input <br>La asignación se realiza dando al parámetro "<b>key</b>" el nombre del campo según aparece en la definición del formulario.<br>Como clase asociada al widget, se usa una clase derivada de Form, que a su vez deriva de Container y este de BaseInput.<br>',
  'input/definitionTest.html'
))
promiseList.push(addTestPromise(
  'Input: campos de tipos básicos',
  'Se prueban los tipos de campos básicos sobre input simples.<br>',
  'input/basicFieldsTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Container',
  'Un campo container genera una vista con los valores de sus campos internos agrupados.',
  'input/containerFieldTest.html'
))
promiseList.push(addTestPromise(
  'Estados en campos de tipo Container',
  'Se pueden definir estado para campos simples que compongan un campo de tipo Container.<br>El tipo <b>State</b> deriva de Enum, siendo el array de valores posibles los estados para los campos del container donde se encuentre.',
  'input/stateTest.html'
))
promiseList.push(addTestPromise(
  'Valor por defecto de los campos de un campo container',
  'Si no se da valor a ninguno de los campos de un container, estos campos permanecen sin valor aunque tengan la propiedad <b>DEFAULT</b>.<br>En el momento en el que se le da valor a cualquiera de los campos del container, los campos con la propiedad DEFAULT toman el valor indicado en esta propiedad, salvo que sea el campo modificado.<br>Pulsando el botón "Enviar" se puede ver por consola cómo el container <i>defaultNotModified</i> no tiene valor en sus campos y como al dar valor al campo no default del container <i>defaultModified</i> tanto este como el campo default tienen valor',
  'input/defaultValuesTest.html'
))
promiseList.push(addTestPromise(
  'Input: uso de INPUTPARAMS en campo de tipo Container',
  'Mismo test anterior, pero utilizando INPUTPARAMS para sobreescribir el widget utilizado para el input Container<br>Un formulario puede parametrizar los inputs por defecto, o parametrizarlos, usando el campo INPUTPARAMS, con el path a los inputs que se quieren parametrizar',
  'input/inputParamsTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Array',
  'Un campo de tipo array genera una vista con una lista ordenada con los valores',
  'input/arrayFieldTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Dictionary',
  'Un diccionario es una agrupación de containers (entradas) que poseen una estructura común, definida en el diccionario.<br>En su visualización tiene que poder verse tanto una lista de las entradas como los valores de cada una de ellas.',
  'input/dictionaryFieldTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo TypeSwitcher',
  'Se trata de campos que pueden cambiar su tipo a cualquier otro que tenga definido internamente.<br>Se selecciona el campo mediante la clave definida en la clave "<b>TYPE_FIELD</b>"<br>Para acceder al valor de uno de los tipos definidos se emplea la clave definida en la clave "<b>CONTENT_FIELD"</b>',
  'input/typeSwitcherFieldTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Container con campos complejos',
  'Se crean los campos agrupados en el container',
  'input/complexContainerTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Array con campos complejos',
  'Cuando los elementos del array son complejos, los elementos de la lista se identifican por su número de orden únicamente.<br>A su lado se crea un área donde mostrar el contenido de los elementos según se van seleccionando',
  'input/complexArrayTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo Dictionary con campos complejos',
  'En este ejemplo se crea un input con campo diccionario para cada tipo complejo.<br>A su lado se crea un área donde mostrar el contenido de los elementos según se van seleccionando.<br>En este caso no se ha dado un valor inicial por lo que los campos aparecen vacíos. Para ver cómo quedaría no hay más que ir rellenándolos desde el UI.',
  'input/complexDictionaryTest.html'
))
promiseList.push(addTestPromise(
  'Input: campo de tipo TypeSwitcher con campos complejos',
  'En este ejemplo se crea un único input con campo typeSwitcher todos los campos complejos definidos como opciones.<br>Dentro de él se crea un área donde mostrar el contenido de los elementos según se van seleccionando.<br>Cuando el tipo es un objeto, por ejemplo un container, puede establecerse el valor mediante los nombres de los campos, al ser similar a aun path.<br>En este caso no se ha dado un valor inicial por lo que los campos aparecen vacíos. Para ver cómo quedaría no hay más que ir rellenándolos desde el UI.',
  'input/complexTypeSwitcherTest.html'
))
promiseList.push(addTestPromise(
  'Validación del valor de un campo',
  'Se establece un valor invalido y se ve si el input inmediatamente muestra el error.<br>',
  'input/fieldValidationTest.html'
))
promiseList.push(addTestPromise(
  'Mostrar campos mediante paths',
  'Para mostrar un campo que no está renderizado se emplea la funcion <b>showPath(<i>path</i>)</b>.<br>Esta función refresca el render de la plantilla para mostrar el elemento indicado por el path que se le pasa como parámetro.',
  'input/pathOnFieldTest.html'
))
promiseList.push(addTestPromise(
  'ComboBox: definición (SOURCE tipo array)',
  'Cuando la fuente de un input es un array se genera un input con un comboBox de opciones asociado.<br>El array debe contener un objeto para cada opción en donde se indique cuál es el valor que adopta el campo y cual es la etiqueta que debe mostarse.<br>',
  'input/comboboxTest.html'
))
promiseList.push(addTestPromise(
  'ComboBox: ComboBox dependientes',
  'Una fuente de un input puede tener varios conjuntos de opciones, seleccionándose uno u otro según una clave externa, indicada en "SOURCE/PATH/"<br>Esta clave externa tiene que tratarse de otro campo con su propia fuente. De esta forma las fuentes quedan relacionadas.',
  'input/dependentComboboxTest.html'
))
promiseList.push(addTestPromise(
  'ComboBox: SOURCE remoto',
  'Puede establecerse un modelo remoto como SOURCE del ComboBox, ya que el widget Model deriva directamente del widget ComboBox.',
  'input/remoteSourceComboboxTest.html'
))
promiseList.push(addTestPromise(
  'ComboBox: SOURCE remotos dependientes',
  'Al igual que con los SOURCE de tipo Array, se pueden hacer depender los SOURCE remotos mediante los parámetros de los dataSource dependientes.',
  'input/dependentRemoteSourceComboboxTest.html'
))
promiseList.push(addTestPromise(
  'Definición remota de campo',
  'Se puede emplear una definción remota de un campo dándole a la clave TYPE (o similar, como VALUETYPE) el valor del path remoto donde se encuentre la definición.',
  'input/remoteFieldDefinitionTest.html'
))
promiseList.push(addTestPromise(
  'Definición remota de formulario',
  'En vez de declararse explicitamente la definición del formulario a mostrar, puede especificarse el <b>modelo</b> al que pertenece dicho formulario y su <b>nombre</b>, encargándose el framework de obtener la definición necesaria para que la vista pueda formarse.<br>Si se desea completar el formulario con un valor concreto, como puede ser en el caso de un formulario de edición, se puede especificar mediante el empleo de <b>keys</b>.<br>',
  'input/remoteFormDefinitionTest.html'
))
promiseList.push(addTestPromise(
  'Definición remota de widget',
  'Puede declararse un widget completo desde la vista, invocándolo mediante el propio nombre de la vista.<br>Al no especificase los campos que se quieren mostrar de entre los que traiga la definición remota, se creará un formulario con todos ellos.<br>Como puede verse en el ejemplo, no es necesario disponer de plantilla o clase, y el namespace del formulario es el que espera el servidor.',
  'input/remoteWidgetDefinitionTest.html'
))
promiseList.push(addTestPromise(
  'Source exclusivo',
  'Prueba de source exclusivo, donde los elementos de un source, no pueden repetirse en el valor asociado. <br>',
  'input/exclusiveSourceTest.html'
))
promiseList.push(addTestPromise(
  'Lista recursiva de DataSource',
  'Este widget muestra el contenido de un DataSource en una lista.<br>Para crearlo es necesario que la clase del widget padre incluya una variable listValue, que toma el valor de los elementos de las listas clicados, y un objeto con las siguientes claves:<br>  - model, dataSource y keys: definen el DS que se va a mostrar  - label: campo del DS que se va a mostrar en la lista<br>  - value: campo del DS que se va a tomar como valor<br>  - listParam: qué elemento de la definición del DS anidado (model, datasource o keys) se va a completar con el valor de la lista actual<br>  - keyParams: en el caso de que listParam sea "keys", se emplea para definir la clave del valor dentro de keys<br>  - innerListParams: un objeto con los mismos elementos que los descritos, para generar la lista anidada.',
  'input/dataSourceRecursiveListTest.html'
))
promiseList.push(addTestPromise(
  'jqxtree',
  'jqxtest docs',
  'input/jqxTreeTest.html'
))


// Widget
promiseList.push(addTestPromise(
  'sivWidget: definition',
  'Un widget se compone de una <b>plantilla HTML</b> y una <b>clase asociada</b>. Estos widgets se invocan mediante una <b>vista</b>.<br>El widget se declara mediante el atributo <b>sivWidget</b>.<br>La clase se declara mediante el atributo <b>widgetCode</b>.<br>La vista invoca un widget dándole al atributo <b>sivView</b> el valor de sivWidget del widget deseado.',
  'widget/definitionTest.html'
))
promiseList.push(addTestPromise(
  'sivWidget: variables de la clase en el widget',
  'Para emplear una variable de la clase en el widget debe declararse con un valor no nulo en preInitialize.<br>Para acceder en el widget al contexto de la clase asociada, y por tanto a sus variables de clase, se emplea el prefijo "<b>*</b>"',
  'widget/classVarTest.html'
))
promiseList.push(addTestPromise(
  'sivWidget: ciclo de vida',
  'El ciclo de vida de un widget es: <u>llamada a preInitialize</u> -> <u>renderizado</u> -> <u>llamada a initialize</u>.<br>En <b>preInitialize</b> aun no se ha renderizado la plantilla, siendo el punto en el que deben declararse las variables usadas en la plantilla.<br>Después de preInitialize y antes de initialize se <b>renderiza la plantilla</b>, bindeando las variables del widget a las variables de la clase.<br>Finalmente, se ejecuta <b>initialize</b>. En ese punto las variables ya están renderizadas por lo que si se cambian en esta fase, la plantilla se renderiza de nuevo automáticamente.<br>En el ejemplo una variable cambia en initialize mediante setInterval.',
  'widget/lifeCycleTest.html'
))
promiseList.push(addTestPromise(
  'sivWidget: acceso al valor de keys en objetos mediante path',
  'Las variables miembro de la clase se pueden navegar como si fueran paths',
  'widget/keyPathTest.html'
))
promiseList.push(addTestPromise(
  'sivWidget: paths dependientes',
  'Los paths pueden depender del valor al que apunten otros paths o del valor de variables.<br>NOTA1: No puede emplearse variables obtenidas mediante composición empleando otras variables: "[%*firstPartVar{%*secondPartVar%}%]"<br>NOTA2: Los paths pueden comenzar opcionalmente por el carácter "/" , pero los paths anidados (que usan {%...%}, en vez de [%...%]) no permiten ese caracter "/" extra.',
  'widget/dependentPathTest.html'
))
promiseList.push(addTestPromise(
  'ParametrizableString (PS): Definición',
  'Se trata de una String en la que se pueden introducir variables que serán resueltas en tiempo de ejecución.<br>Las PS pueden realizar las siguientes operaciones:<br>Sustitución: sustituye el valor indicado entre "[%" y "%]"<br>Sustitución anidada: sustituye primero el valor indicado entre "{%" y "%}" y posteriormente el resultado que haya entre "[%" y "%]"<br>Verificación: se realiza la verificación indicada entre "[%" y "{%" y solo si el resultado es "true" se ejecuta la sustitución indicada entre "{%" y "%}"<br>Transformación: Después de las posibles verificaciones y antes de la sustitución, se toma el valor final a renderizar y se modifica según lo indicado entre "{%" y "%}", separado del nombre de la variable mediante ":"',
  'widget/psDefinitionTest.html'
))
promiseList.push(addTestPromise(
  'Transparencia de nodos',
  'Los nodos html que contienen tags de tipo SivWidget, SivView o SivLoop, no estan incluidos en el DOM final.<br>En el siguiente ejemplo, los nodos que contienen SivWidget,SivView y SivLoop, establecen colores de fondo, que, como se ve, no están en la salida.',
  'widget/nodeTransparencyTest.html'
))
promiseList.push(addTestPromise(
  'Rootnode',
  'Los nodos que componen un widget son accesibles a traves de la propiedad rootNode.<br>RootNode contiene todos los nodos hijos del subwidget renderizado (es un objeto jQuery). Es por ello que no es accesible en preInitialize (aún no se ha renderizado el widget).<br>En este ejemplo, se usa rootNode para encontrar los hijos, dentro del widget actual, que tienen una clase de estilo',
  'widget/rootNodeDefinitionTest.html'
))
promiseList.push(addTestPromise(
  'Promesas en preInitialize',
  'En muchas ocasiones, las variables necesarias para crear un widget, no estan disponibles al llamar a preInitialize. <br>Cuando el contenido de un Widget depende, por ejemplo, de una llamada ajax, y hay que esperar a que ésta llamada termine, desde preInitialize se retorna una promesa.<br>El widget se procesará, cuando la promesa se haya resuelto.<br>En el ejemplo, se utiliza un setTimeout() para simular la llamada ajax.',
  'widget/preinitializePromiseTest.html'
))
promiseList.push(addTestPromise(
  'BaseTypedObject y Widgets',
  'La API de tipos, y los API de widgets, son independientes.Una y otra se pueden usar por separado.<br>Cuando se combinan, desde el punto de vista del Widget, es una variable cualquiera, y se accede de la misma forma.<br>Como cualquier otro BaseTypedObject, hay que llamar a destruct cuando ya no son necesarios.<br>En este ejemplo, se define un BTO en el preInitialize, y se le asigna un valor, usado al renderizar el widget.<br>En el initialize, se modifica el valor del BTO, y, en el destructor del widget, se llama al destructor del BTO.',
  'widget/btoTest.html'
))
promiseList.push(addTestPromise(
  'BTO Remoto',
  'Si la definicion del BTO es remota, es equivalente a cualquier llamada Ajax.<br>Esto significa, retornar una promesa de preInitialize, y resolverla cuando el BTO esté listo.<br>Un BTO remoto puede ser un formulario, o la salida de un datasource.En este caso se utiliza un datasource, y se itera sobre el resultado.<br>La llamada a unfreeze() del datasource, dispara la request y retorna una promesa, que es la que es devuelva en preInitialize',
  'widget/remoteBTOTest.html'
))
promiseList.push(addTestPromise(
  'Datasource',
  'Tratamos de sacar la información de un BTO',
  'widget/dataSourceTest.html'
))
promiseList.push(addTestPromise(
  'Factorias',
  'Este ejemplo utiliza la funcion stringToContextAndObject para crear una pequeña factoria de widgets.<br>Esta factoria simple, utiliza el nombre del tipo del BTO, para instanciar un widget que renderiza ese tipo.<br>Se declara una clase base de renderizado de tipos, de las que deriva una clase para renderizar Strings, otra para renderizar Integer, y en el widget principal, se declara un BTO con esos tipos.<br>Se itera sobre los campos, y,usando sivCall, se crean las instancias de los widgets asociados al tipo.<br>Como nota adicional, los widgets creados por el widget Factoria, se almacenan en una variable, y se destruyen en el destruct(), junto con el bto.<br>',
  'widget/widgetFactoryTest.html'
))
promiseList.push(addTestPromise(
  'Esperando que los subwidgets sean creados',
  'Se crean vistas que dependen de otras vistas, y que se van a resolver en momentos diferentes,<br>La vista raiz debe esperar a que todas las subVistas esten listas, antes de mostrar un mensaje.<br>',
  'widget/waitCompleteTest.html'
))
promiseList.push(addTestPromise(
  'Comunicación entre widgets: eventos',
  'Se muestra la comunicación mediante eventos entre dos widget hijos mediante su padre.<br>',
  'widget/widgetCommunicationTest.html'
))
