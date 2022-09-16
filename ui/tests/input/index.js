document.getElementsByTagName('title')[0].innerHTML = 'Tests inputs'

var promiseList = []

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
