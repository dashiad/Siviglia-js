runTest("Widget Minimo",
  "Widget minimo, con una plantilla que contiene solo un texto, y una clase vacia",
  '<div data-sivWidget="Test.Minimal" data-widgetCode="Test.Minimal">' +
  '<span>Hello world</span>' +
  '</div>',
  '<div data-sivView="Test.Minimal"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        'Minimal': {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {

            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)
runTest("SivValue",
  "Uso simple de SivValue: Una variable asignada en preInitialize, es renderizada en el widget",
  '<div data-sivWidget="Test.Widget" data-widgetCode="Test.Widget">' +
  '<span data-sivValue="[%*message%]"></span>' +
  '</div>',
  '<div data-sivView="Test.Widget"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'Widget':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.message="Hello World! (2)"
            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)
runTest("SivValue2",
  "El valor de sivValue es una ParametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable. Puede referenciar más de una, texto, y expresiones complejas.<br>"+
  "Las variables utilizadas en las expresiones, comienzan por el prefijo '*' cuando hacen referencia a una variable miembro de la clase asociada al widget",
  '<div data-sivWidget="Test.SivValue2" data-widgetCode="Test.SivValue2">' +
  '<span data-sivValue="Esto <h2>[%*message1%]</h2> una <b><i>[%*message2%]</i></b>"></span>' +
  '</div>',
  '<div data-sivView="Test.SivValue2"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivValue2':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.message1="es"
              this.message2="prueba"
            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)
runTest("SivValue3",
  "El ciclo de vida de un widget es: llamada a preInitialize, renderizado, llamada a initialize.<br>" +
  "Esto significa que en preInitialize aun no se ha renderizado la plantilla, y todas las variables usadas en la plantilla, deben inicializarse.<br>" +
  "Cuando se llama a initialize, ya está creado el widget. Las variables usadas en el widget, están bindeadas a las variables de la clase.<br>" +
  "En este ejemplo, en initialize, se cambia una variable en un setInterval, actualizandose automáticamente la plantilla. En preInitialize, se inicializa la variable (es requerido para que exista al hacer el primer renderizado de la plantilla), y en initialize se crea el intervalo.",
  '<div data-sivWidget="Test.SivValue3" data-widgetCode="Test.SivValue3">' +
  '<span data-sivValue="[%*counter%]"></span>' +
  '</div>',
  '<div data-sivView="Test.SivValue3"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivValue3':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.counter=0;
            },
            initialize: function (params) {
              setInterval(function(){this.counter++}.bind(this),1000);
            }
          }
        }
      }

    })
  }
)
runTest("SivValue4",
  "Por defecto, sivValue establece el innerHTML de un campo.Es posible establecer otras parejas atributo-valor, separando cada pareja por ::, y el nombre y el valor por | ",
  '<style type="text/css">.simpleClass {font-weight:bold;color:green}</style>' +
  '<div data-sivWidget="Test.SivValue4" data-widgetCode="Test.SivValue4">' +
  '<span data-sivValue="class|[%*assignedClass%]::title|El titulo es:[%*title%]">Contenido simple</span>' +
  '</div>',
  '<div data-sivView="Test.SivValue4"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivValue4':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.assignedClass="simpleClass";
              this.title="Titulo asignado desde el widget";
            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)
runTest("SivValue5",
  "Las variables miembro de la clase, se pueden navegar como si fueran paths",
  '<div data-sivWidget="Test.SivValue5" data-widgetCode="Test.SivValue5">' +
  '<span data-sivValue="[%*test/0/key%]"></span>' +
  '</div>',
  '<div data-sivView="Test.SivValue5"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivValue5':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.test=[
                {
                  key:"Hello world!"
                }
              ]
            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)
runTest("SivValue6",
  "Los paths, a su vez, pueden depender de otros paths.<br>" +
  "En este test, la variable usada en sivValue, es, a su vez, dependiente de otra variable, que va cambiando segun un intervalo.<br>"+
  "Los paths pueden comenzar opcionalmente por el carácter / , pero los paths anidados (que usan {%...%}, en vez de [%...%]) no permiten ese caracter / extra.",
  '<div data-sivWidget="Test.SivValue6" data-widgetCode="Test.SivValue6">' +
  '<span data-sivValue="[%/*test/{%*index%}/key%]"></span>' +
  '</div>',
  '<div data-sivView="Test.SivValue6"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivValue6':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.index=0;
              this.test=[
                {
                  key:"Hello world!"
                },
                {
                  key:"Second Message"
                }
              ]
            },
            initialize: function (params) {
              setInterval(function(){this.index=(this.index+1)%2;}.bind(this),1000);
            }
          }
        }
      }

    })
  }
)
runTest("SivLoop",
  "SivLoop atraviesa una variable iterable.<br>" +
  "En cada iteracion, establece una variable contextual (prefijo @), que apunta al elemento actual.",
  '<div data-sivWidget="Test.SivLoop" data-widgetCode="Test.SivLoop">' +
  '<div data-sivLoop="*simpleArray" data-contextIndex="current">' +
  '<div data-sivValue="[%@current%]"></div>' +
  '</div>' +
  '</div>',
  '<div data-sivView="Test.SivLoop"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivLoop':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.simpleArray=["cad1","cad2","cad3"];
            },
            initialize: function (params) {

            }
          }
        }
      }

    })
  }
)
runTest("SivLoop2",
  "Es posible anidar varios sivloops, utilizando el indice del externo como fuente.<br>En este ejemplo, el array interno itera sobre un dictionary.<br>Un loop, ademas de la variable de contexto (que apunta a los valores), tambien define la variable con el sufijo -index, que apunta a la key. ",
  '<div data-sivWidget="Test.SivLoop2" data-widgetCode="Test.SivLoop2">'+
  '<div data-sivLoop="*simpleArray" data-contextIndex="current">' +
  '<div style="border:1px solid black">'+
  '<div data-sivLoop="/@current" data-contextIndex="inner">' +
  '<div data-sivValue="[%/@inner-index%] : [%/@inner%]"></div>'+
  '</div>' +
  '</div>' +
  '</div>' +
  '</div>',
  '<div data-sivView="Test.SivLoop2"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivLoop2':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.simpleArray=[
                {a:"cad1",b:"cad2",c:"cad3"},
                {a:"cad4",b:"cad5",c:"cad6"},
                {a:"cad7",b:"cad8",c:"cad9"},

              ];
            },
            initialize: function (params) {

            }
          }
        }
      }

    })
  }
)
runTest("SivCall","SivCall, junto con sivparams, realiza una llamada al metodo especificado, que recibe como parametros tanto el nodo que contiene sivCall, como los parametros especificados.<br>"+
  "Estos parametros se especifican como un objeto json, y puede contener referencias a variables de clase y de contexto.<br>"+
  "Este ejemplo es el mismo anterior, estableciendo el contenido de los nodos usando SivCall, en vez de SivValue.<br>"+
  "(Nota: para especificar el json dentro de un atributo html, utilizo comillas simples para el atributo, de forma que no hay que escapear las comillas dobles del json)",
  '<div data-sivWidget="Test.SivCall" data-widgetCode="Test.SivCall">'+
  '<div data-sivLoop="*dictionariesArray" data-contextIndex="dictionary"><div style="border:1px solid black">'+
  '<div data-sivLoop="@dictionary" data-contextIndex="value"><div data-sivCall="setContent" data-sivparams=\'{"indice":"@value-index","valor":"/@value"}\'></div>'+
  '</div></div></div></div>',
  '<div data-sivView="Test.SivCall"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivCall':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.dictionariesArray=[
                {a:"cad1",b:"cad2",c:"cad3"},
                {a:"cad4",b:"cad5",c:"cad6"},
                {a:"cad7",b:"cad8",c:"cad9"},

              ];
            },
            initialize: function (params) {

            },
            setContent:function(node,params)
            {
              node.html(params.indice+" : "+params.valor);
            }
          }
        }
      }

    })
  }
)
runTest("SivEvent","SivEvent, junto a sivcallback y sivParams, se utiliza para asignar un gestor de eventos.<br>"+
  "Aunque es posible asignar más de 1 evento, el callback y los parámetros son compartidos.<br>"+
  "El nombre de los eventos es el usado por jQuery, y, en caso de especificar más de uno, debe ir separado por comas.<br>",
  '<div data-sivWidget="Test.SivEvent" data-widgetCode="Test.SivEvent">'+
  '<div data-sivEvent="click" data-sivcallback="onclicked" data-sivparams=\'{"phrase":"An alert"}\'>Click aqui</div>'+
  '</div>',
  '<div data-sivView="Test.SivEvent"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivEvent':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
            },
            initialize: function (params) {

            },
            onclicked:function(node,params)
            {
              alert(params.phrase);
            }
          }
        }
      }

    })
  }
)
runTest("SivIf","SivIf evalua una expresion, y en caso de evaluar a true, renderiza el contenido de su tag."+
  "En este ejemplo, se alterna el valor de una variable, lo que alterna el contenido mostrado.<br>"+
  "la expresion del SivIf se evalua con eval de javascript, por lo que admite los condicionales de javascript",
  '<div data-sivWidget="Test.SivIf" data-widgetCode="Test.SivIf">'+
  '<div data-sivIf="[%*flipper%] == 1"><div style="background-color:blue;color:white">Valor Uno</div></div>'+
  '<div data-sivIf="[%*flipper%] == 0"><div style="background-color:green;color:white">Valor Cero</div></div>'+
  '</div>',
  '<div data-sivView="Test.SivIf"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivIf':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.flipper=1;
            },
            initialize: function (params) {
              setInterval(function(){this.flipper=(this.flipper+1)%2}.bind(this),1000);
            }
          }
        }
      }

    })
  }
)
runTest("SivIf 2","Prueba de funcionamiento de la regeneración del contenido de sivIf."+
  "Se prueba cómo sivIf regenera los contenidos a medida que cambia.Especificamente, qué ocurre con los sivId definidos dentro de un sivIf.<br>"
  ,
  '<div data-sivWidget="Test.SivIf" data-widgetCode="Test.SivIf">'+
  '<div data-sivIf="[%/*flipper%] == 1"><div data-sivId="target"></div><div style="background-color:blue;color:white">Valor Uno</div></div>'+
  '<div data-sivIf="[%/*flipper%] == 0"><div data-sivId="target"></div><div style="background-color:green;color:white">Valor Cero</div></div>'+
  '</div>',
  '<div data-sivView="Test.SivIf"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivIf':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.flipper=1;
            },
            initialize: function (params) {
              setInterval(function(){
                this.flipper=(this.flipper+1)%2;
                if(typeof this.flipper!=="undefined")
                  this.target.html("**"+this.flipper+"**");
              }.bind(this),1000);
            }
          }
        }
      }

    })
  }
)
runTest("SivView","Desde un widget, es posible instanciar otros widgets usando SivView desde dentro de la plantilla.<br>"+
  "La plantilla padre puede pasar parámetros a las vistas hijas, usando sivParams.Estos parámetros se reciben en los métodos preInitialize e <br>"+
  "Los parámetros siguen bindeados, por lo que un cambio en las variables pasadas como parametros, provoca el repintado de las vistas.<br>" +
  "El siguiente ejemplo, pasa 2 variables (una de ellas, un valor fijo, y la otra, una variable bindeada del widget) a la vista hija.Se cambia el valor de la variable, y se refresca la vista hija.<br>"+
  "Primero se define las vistas hijas, y luego la vista padre.<br>",
  '<div data-sivWidget="Test.SubView" data-widgetCode="Test.SubView">'+
  '<div data-sivValue="[%*mensajeFijo%]"></div>'+
  '<div data-sivValue="[%*mensajeVariable%]"></div>'+
  '</div>'+
  '<div data-sivWidget="Test.SivViews" data-widgetCode="Test.SivViews">'+
  '<div data-sivView="Test.SubView" data-sivparams=\'{"fijo":"Texto Fijo","variable":"/*variable"}\'></div></div>',
  '<div data-sivView="Test.SivViews"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SubView':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params)
            {
              this.mensajeFijo=params.fijo;
              this.mensajeVariable=params.variable;
            },
            initialize:function(params){}
          }
        },
        'SivViews':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.counter=1;
              this.variable="Mensaje Variable";
            },
            initialize: function (params) {
              setInterval(function(){this.variable="Mensaje Cambiado "+this.counter;this.counter=(this.counter+1)%2}.bind(this),1000);
            }
          }
        }
      }

    })
  }
)

runTest("Transparencia de nodos","Los nodos html que contienen tags de tipo SivWidget, SivView o SivLoop, no estan incluidos en el DOM final.<br>"+
  "En el siguiente ejemplo, los nodos que contienen SivWidget,SivView y SivLoop, establecen colores de fondo, que, como se ve, no están en la salida.",
  '<div data-sivWidget="Test.Transp" data-widgetCode="Test.Transp" style="background-color:yellow">'+
  '<div data-sivLoop="/*anArray" data-contextIndex="current" style="background-color:blue"><div data-sivValue="[%@current%]"></div></div>'+
  '</div>',
  '<div data-sivView="Test.Transp" style="background-color:green"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'Transp':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params)
            {
              this.anArray=["a","b","c"];
            },
            initialize:function(params){}
          }
        }
      }

    })
  }
)

runTest("Rootnode","Los nodos que componen un widget son accesibles a traves de la propiedad rootNode.<br>"+
  "RootNode contiene todos los nodos hijos del subwidget renderizado (es un objeto jQuery). Es por ello que no es accesible en preInitialize (aún no se ha renderizado el widget).<br>"+
  "En este ejemplo, se usa rootNode para encontrar los hijos, dentro del widget actual, que tienen una clase de estilo",
  '<div data-sivWidget="Test.RootNode" data-widgetCode="Test.RootNode"><div>'+
  '<div class="a">A</div><div class="b">B</div><div class="a">A</div></div>'+
  '</div>',
  '<div data-sivView="Test.RootNode" style="background-color:green"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'RootNode':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params)
            {
            },
            initialize:function(params){
              $(".a",this.rootNode).css("background-color","blue");
            }
          }
        }
      }

    })
  }
)
runTest("SivId y ByCode","Los nodos que contienen el atributo data-sivId, se mapean a variables con el mismo nombre en la clase del widget <br>"+
  "Todos los ejemplos hasta ahora, han instanciado los widgets desde HTML, con un tag sivView. En este ejemplo, se instancia una vista a traves de código.<br>"+
  "Los parámetros recibidos son: 1)Nombre de la template, 2)Parametros (recibidos en preInitialize), 3)Bloques (actualmente sin uso), 4)Placeholder (establer a un div vacio), 5)instancia de Siviglia.Path.ContextStack<br>"+
  "En el ejemplo, se crea una instancia del widget Test.Sample, dentro del nodo identificado por sivId=here<br>"+
  "Una vez creada la instancia, se llama a su metodo __build, que devuelve una promesa.El widget estará construido cuando la promesa se resuelva<br>"+
  "Un punto importante, es que los widgets creados desde código, deben ser destruidos cuando no son necesarios (en este caso, se hace en el destruct del propio widget)",
  '<div data-sivWidget="Test.Sample" data-widgetCode="Test.Sample"><b data-sivValue="[%*receivedParam%]"></b></div>'+
  '<div data-sivWidget="Test.ByCode" data-widgetCode="Test.ByCode">'+
  '<div data-sivId="here" style="background-color:yellow"></div>'+
  '</div>',
  '<div data-sivView="Test.ByCode" style="background-color:green"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'Sample':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params) {
              this.receivedParam = params.sentParam;
              console.dir(params);
            },
            initialize:function(params){}
          }
        },
        'ByCode':{
          inherits: "Siviglia.UI.Expando.View",
          destruct:function() {
            if (this.innerWidget) this.innerWidget.destruct();
          },
          methods:{
            preInitialize:function(params) {
              this.innerWidget=null;
            },
            initialize:function(params) {
              this.innerWidget = new Test.Sample(
                "Test.Sample",
                {sentParam:"Texto Enviado"},{},
                $("<div></div>"),
                new Siviglia.Path.ContextStack()
              );

              this.innerWidget.__build().then(function(instance) {
                this.here.append(this.innerWidget.rootNode);
              }.bind(this));
            }
          }
        }
      }
    })
  }
)
runTest("Promesas en preInitialize","En muchas ocasiones, las variables necesarias para crear un widget, no estan disponibles al llamar a preInitialize. <br>"+
  "Cuando el contenido de un Widget depende, por ejemplo, de una llamada ajax, y hay que esperar a que ésta llamada termine, desde preInitialize se retorna una promesa.<br>"+
  "El widget se procesará, cuando la promesa se haya resuelto.<br>"+
  "En el ejemplo, se utiliza un setTimeout() para simular la llamada ajax.",
  '<div data-sivWidget="Test.Promise" data-widgetCode="Test.Promise">'+
  '<div data-sivValue="[%*timerValue%]"></div>'+
  '</div>',
  '<div data-sivView="Test.Promise" style="background-color:green"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'Promise':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params)
            {
              var returnedPromise=$.Deferred();
              setTimeout(function(){
                this.timerValue="Timer finalizado";
                returnedPromise.resolve();
              }.bind(this),3000);
              return returnedPromise;
            },
            initialize:function(params){
            }
          }
        }
      }

    })
  }
)
runTest("BaseTypedObject y Widgets","La API de tipos, y los API de widgets, son independientes.Una y otra se pueden usar por separado.<br>"+
  "Cuando se combinan, desde el punto de vista del Widget, es una variable cualquiera, y se accede de la misma forma.<br>"+
  "Como cualquier otro BaseTypedObject, hay que llamar a destruct cuando ya no son necesarios.<br>"+
  "En este ejemplo, se define un BTO en el preInitialize, y se le asigna un valor, usado al renderizar el widget.<br>"+
  "En el initialize, se modifica el valor del BTO, y, en el destructor del widget, se llama al destructor del BTO.",
  '<div data-sivWidget="Test.BTO" data-widgetCode="Test.BTO">'+
  '<div data-sivLoop="*bto/arrField" data-contextIndex="current">' +
  '<div data-sivValue="[%@current/fieldA%]"></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.BTO" style="background-color:green"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'BTO':{
          inherits: "Siviglia.UI.Expando.View",
          destruct:function()
          {
            this.bto.destruct();
          },
          methods:{
            preInitialize:function(params)
            {
              this.bto=new Siviglia.model.BaseTypedObject({
                "FIELDS":{
                  "arrField":{
                    "TYPE":"Array",
                    "ELEMENTS":{
                      "TYPE":"Container",
                      "FIELDS":{
                        "fieldA":{"TYPE":"String"}
                      }
                    }
                  }
                }
              });
              this.bto.setValue({arrField:[{fieldA:"Campo1"},
                  {fieldA:"Campo2"},
                  {fieldA:"Campo3"}]})
            },
            initialize:function(params){
              setTimeout(function(){
                this.bto.arrField.push({fieldA:"Added"});
              }.bind(this),1000);
            }
          }
        }
      }

    })
  }
)
runTest("Layouts alternativos",
  "En este ejemplo se especifican varios layouts, todos apuntando al mismo data-widgetCode.<br>"+
  "Cuando se instancia una vista de un widget es posible especificar un layout alternativo con el atributo data-sivLayout.<br>"+
  "Esta característica es uno de los pilares del sistema de widgets. Significa una separación entre la gestión del código de un widget, y el html que lo renderiza.<br>"+
  "El separar las clases de los layouts, permite que haya varias formas de mostrar un mismo elemento conceptual.<br>"+
  "En este ejemplo, se utiliza un mismo widget, simulando un menu, que se muestra usando varios layouts diferentes.",
  '<div data-sivWidget="Test.AltLayout" data-widgetCode="Test.AltLayout">'+
  '<ul>'+
  '<div data-sivLoop="*options" data-contextIndex="current">'+
  '<li><a data-sivValue="href|[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a></li>'+
  '</div>'+
  '</ul>'+
  '</div>'+
  '<div data-sivWidget="Test.AltLayout2" data-widgetCode="Test.AltLayout">'+
  '<div>'+
  '<div data-sivLoop="*options" data-contextIndex="current">'+
  '<div style="float:left;border-radius:8px;background-color:green;color:white;padding:4px;margin-right:3px"><a data-sivValue="href|[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a>' +
  '</div>'+
  '</div>' +
  '</div>' +
  '<div style="clear:both"></div>' +
  '</div>',
  '<div data-sivView="Test.AltLayout"></div><div style="height:10px"></div><div data-sivView="Test.AltLayout" data-sivLayout="Test.AltLayout2"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'AltLayout':{
          inherits: "Siviglia.UI.Expando.View",
          methods:{
            preInitialize:function(params)
            {
              this.options=[
                {"link":"http://elmundo.es","text":"ElMundo"},
                {"link":"http://cnn.com","text":"CNN"},
                {"link":"http://wwww.google.com","text":"Google"}
              ];
            },
            initialize:function(params){
            }
          }
        }
      }

    })
  }
)
runTest("BTO Remoto","Si la definicion del BTO es remota, es equivalente a cualquier llamada Ajax.<br>"+
  "Esto significa, retornar una promesa de preInitialize, y resolverla cuando el BTO esté listo.<br>"+
  "Un BTO remoto puede ser un formulario, o la salida de un datasource.En este caso se utiliza un datasource, y se itera sobre el resultado.<br>"+
  "La llamada a unfreeze() del datasource, dispara la request y retorna una promesa, que es la que es devuelva en preInitialize",
  '<div data-sivWidget="Test.BTO2" data-widgetCode="Test.BTO2">'+
  '<div data-sivLoop="*ds/data" data-contextIndex="current">'+
  '<div style="border:1px solid black;margin:2px">'+
  '<div data-sivValue="[%@current/id_site%] : [%@current/host%]"></div>'+
  '</div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.BTO2" style="background-color:green"></div>' ,
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'BTO2':{
          inherits: "Siviglia.UI.Expando.View",
          destruct:function()
          {
            this.ds.destruct();
          },
          methods:{
            preInitialize:function(params)
            {
              this.ds=new Siviglia.Model.DataSource("/model/web/Site","FullList",{});
              this.ds.freeze();
              this.ds.settings.__start=0;
              this.ds.settings.__count=5;
              return this.ds.unfreeze();
            },
            initialize:function(params){
            }
          }
        }
      }

    })
  }
)
runTest("Factorias","Este ejemplo utiliza la funcion stringToContextAndObject para crear una pequeña factoria de widgets.<br>"+
  "Esta factoria simple, utiliza el nombre del tipo del BTO, para instanciar un widget que renderiza ese tipo.<br>"+
  "Se declara una clase base de renderizado de tipos, de las que deriva una clase para renderizar Strings, otra para renderizar Integer, y en el widget principal, se declara un BTO con esos tipos.<br>"+
  "Se itera sobre los campos, y,usando sivCall, se crean las instancias de los widgets asociados al tipo.<br>"+
  "Como nota adicional, los widgets creados por el widget Factoria, se almacenan en una variable, y se destruyen en el destruct(), junto con el bto.<br>",
  '<div data-sivWidget="Test.Factory" data-widgetCode="Test.Factory">'+
  '<div data-sivLoop="*mybto/__definition/FIELDS" data-contextIndex="current">'+
  '<div data-sivCall="getTypeWidget" data-sivParams=\'{"field":"@current-index","type":"@current/TYPE"}\'></div>'+
  // '<div data-sivValue="[%@current/TYPE%]"></div>'+
  '</div>'+
  '</div>'+
  '<div data-sivWidget="Test.Integer" data-widgetCode="Test.Integer"><div style="background-color:green" data-sivValue="Entero: [%*currentValue%]"></div></div>'+
  '<div data-sivWidget="Test.String" data-widgetCode="Test.String"><div style="background-color:yellow" data-sivValue="Cadena: [%*currentValue%]"></div></div>'
  ,
  '<div data-sivView="Test.Factory"></div>' ,
  function() {
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'TypeRenderer':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.currentValue=params.field.getValue();
            },
            initialize: function(params){}
          }
        },
        'Integer': {
          inherits: "Test.TypeRenderer"
        },
        'String': {
          inherits:"Test.TypeRenderer"
        },
        'Factory': {
          inherits: "Siviglia.UI.Expando.View",
          destruct: function() {
            this.mybto.destruct();
            for(var k in this.createdWidgets)
              this.createdWidgets[k].destruct();
          },
          methods:{
            preInitialize: function(params) {
              this.mybto = new Siviglia.model.BaseTypedObject({
                "FIELDS":{
                  "textField":{
                    "TYPE":"String"
                  },
                  "integerField":{
                    "TYPE":"Integer"
                  }
                }
              });
              this.mybto.setValue({"textField":"Campo de texto","integerField":11223344})
              this.createdWidgets=[];
            },
            initialize: function(params){},
            getTypeWidget: function(node,params) {
              var field = this.mybto["*"+params.field];
              var type = params.type;
              var widgetName = "Test."+type;
              var ctx = Siviglia.Utils.stringToContextAndObject(widgetName);
              var targetWidget = new ctx.context[ctx.object](
                widgetName,
                {field:field},
                {},
                $("<div></div>"),
                new Siviglia.Path.ContextStack()
              );
              targetWidget.__build().then(function(instance){
                node.append(instance.rootNode);
              })
              this.createdWidgets[params.field] = targetWidget;
            }
          }
        }
      }

    })
  }
)
runTest("Inputs Simples","Primera prueba de inputs de JqxWidgets.<br>"+
  "Se prueban los inputs sobre campos simples.<br>" +
  "Se usa una clase derivada de Form, que a su vez deriva de Container.<br>" +
  "La funcion getInput llamada desde la plantilla esta definida en Container.<br>",
  '<div data-sivWidget = "Test.Input1" data-widgetParams="" data-widgetCode="Test.Input1">'+
  '<div class="type">'+
  '<div class="label">Cadena</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"stringType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Enum</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"enumType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Entero</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"integerType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Decimal</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"decimalType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Texto</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"textType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Boolean</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"booleanType"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input1"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input1": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  "stringType": {
                    TYPE: "String",
                    LABEL: "Hola",
                    MINLENGTH: 3,
                    HELP: "La ayuda"
                  },
                  enumType: {
                    TYPE: "Enum",
                    LABEL: "EnumType",
                    VALUES: ["One", "Two", "Three"],
                  },
                  integerType: {
                    TYPE: "Integer",
                    LABEL:"IntegerType",
                    MAX: 1000
                  },
                  decimalType: {
                    TYPE: "Decimal",
                    LABEL:"DecimalType",
                    NINTEGERS: 5,
                    NDECIMALS: 2
                  },
                  textType: {
                    TYPE: "Text",
                    LABEL:"textType",
                  },
                  booleanType: {
                    TYPE: "Boolean",
                    LABEL:"BooleanType",
                  }
                }
              });
              this.typedObj.stringType = "abcde";
              this.typedObj.enumType = "Two";
              this.typedObj.integerType = 10;
              this.typedObj.decimalType = 8.3;
              this.typedObj.textType = "Esta es una prueba";
              this.typedObj.booleanType = true;
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {},
            show: function () {},
          }
        }
      }
    })
  }
)
runTest("Inputs Simples","Prueba parcial de errores.Se establece un valor invalido, y se ve si el input inmediatamente muestra el error.<br>",

  '<div data-sivWidget="Test.Input1" data-widgetParams="" data-widgetCode="Test.Input1">'+
  '<div class="type">'+
  '<div class="label">Cadena</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"stringType"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input1"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input1": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  "stringType": {
                    TYPE: "String",
                    MINLENGTH: 3,
                    LABEL: "Hola",
                    HELP: "La ayuda"
                  }
                }
              });
              try {
                this.typedObj.stringType = "ab";
              } catch(e) {} // Aqui ignoramos la excepcion, queremos que se pinte el input con el error.
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {},
            show: function () {},
          }
        }
      }
    })
  }
)
runTest("Inputs Simples","Segunda prueba de JqxWidgets<br>"+
  "Se prueba un entero con un source, y una serie de campos enteros y de cadena, con sources enlazados, de forma que unos dependen de otros. .<br>"+
  "",
  '<div data-sivWidget="Test.Input2" data-widgetParams="" data-widgetCode="Test.Input2">'+
  '<div class="type">'+
  '<div class="label">Combo con source Array</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"comboType"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Combo enlazado 1, con source Array</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"comboType2a"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Combo enlazado 2 (dependiente) con source Array</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"comboType2b"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">Combo enlazado 3 (dependiente de 2) con source Array</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"comboType2c"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input2"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input2": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  comboType: {
                    LABEL:"ComboType",
                    "TYPE": "Integer",
                    "SOURCE": {
                      "TYPE": "Array",
                      "LABEL": "message",
                      "VALUE": "a",
                      "DATA": [
                        {"a": 1, "message": "Opcion 1"},
                        {"a": 2, "message": "Opcion 2"},
                        {"a": 3, "message": "Opcion 3"},
                        {"a": 4, "message": "Opcion 4"},
                        {"a": 5, "message": "Opcion 5"},
                        {"a": 6, "message": "Opcion 6"},
                        {"a": 7, "message": "Opcion 7"}
                      ],
                    }
                  },
                  comboType2a: {
                    "LABEL":"ComboType-2A",
                    "TYPE": "String",
                    "SOURCE": {
                      "TYPE": "Array",
                      "LABEL": "label",
                      "VALUE": "val",
                      "DATA": [
                        {"val": "one", "label": "Sel one"},
                        {"val": "two", "label": "Sel two"}
                      ],
                    }
                  },
                  comboType2b: {
                    "LABEL":"ComboType-2B",
                    "TYPE": "Integer",
                    "SOURCE": {
                      "TYPE": "Array",
                      "LABEL": "message",
                      "VALUE": "a",
                      "PATH": "/{%#../comboType2a%}",
                      "DATA": {
                        "one": [
                          {"a": 1, "message": "Opcion 1"},
                          {"a": 2, "message": "Opcion 2"},
                        ],
                        "two": [
                          {"a": 10, "message": "xxOpcion 1"},
                          {"a": 11, "message": "xxOpcion 2"},
                        ]
                      },
                    }
                  },
                  comboType2c: {
                    "LABEL":"ComboType-2C",
                    "TYPE": "Integer",
                    "SOURCE": {
                      "TYPE": "Array",
                      "LABEL": "message",
                      "VALUE": "a",
                      "PATH": "/{%#../comboType2b%}",
                      "DATA": {
                        1: [
                          {"a": 20, "message": "Third - 1 - 1"},
                          {"a": 21, "message": "Third - 1 - 2"}
                        ],
                        2: [
                          {"a": 22, "message": "Third - 2 - 1"},
                          {"a": 23, "message": "Third - 2 - 2"}
                        ],
                        10: [
                          {"a": 24, "message": "Third - 3 - 1"},
                          {"a": 25, "message": "Third - 3 - 2"}
                        ],
                        11: [
                          {"a": 26, "message": "Third - 4 - 1"},
                          {"a": 27, "message": "Third - 4 - 2"}
                        ]
                      },
                    }
                  },
                }
              });
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {},
            show: function () {},
          }
        }
      }
    })
  }
)
runTest("Esperando que los subwidgets sean creados","Se crean vistas que dependen de otras vistas, y que se van a resolver en momentos diferentes,<br>"+
  "La vista raiz debe esperar a que todas las subVistas esten listas, antes de mostrar un mensaje.<br>"+
  "",
  '<div data-sivWidget="Test.Waiter1" data-widgetCode="Test.Waiter1"></div>'+
  '<div data-sivWidget="Test.Waiter2" data-widgetCode="Test.Waiter2">'+
  '   <div data-sivView="Test.Waiter1" data-sivParams=\'{"wait":"*randSecs"}\'></div>'+
  '</div>'+
  '<div data-sivWidget="Test.Waiter3" data-widgetCode="Test.Waiter3"></div>'+
  '<div data-sivWidget="Test.WaiterTest"  data-widgetCode="Test.WaiterTest">'+
  '   <div data-sivView="Test.Waiter1" data-sivParams=\'{"wait":"*randSecs"}\'></div>'+
  '   <div data-sivView="Test.Waiter2" data-sivParams=\'{"wait":"*randSecs"}\'></div>'+
  '   <div data-sivView="Test.Waiter3" data-sivParams=\'{"wait":"*randSecs"}\'></div>'+
  '</div>',
  '<div data-sivView="Test.WaiterTest"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "WaiterTest": {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.waitComplete().then(function() {
                this.showMessage();
              }.bind(this))
              this.randSecs=this.getRandSeconds();

              if (
                typeof params !== "undefined" &&
                typeof params.wait !== "undefined"
              ) {
                var p=SMCPromise();
                setTimeout(function(){
                  p.resolve()}.bind(this),params.wait
                );
                return p;
              }
            },
            initialize: function (params) {},
            showMessage: function() {console.log("WAITERTEST: LISTO")},
            getRandSeconds: function() {
              return 2000;
            }
          }
        },
        "Waiter1": {
          inherits:"Test.WaiterTest",
          methods: {
            showMessage: function() {console.log("WAITERTEST 1: LISTO")},
            getRandSeconds: function() {
              return 1000;
            }
          }
        },
        "Waiter2": {
          inherits: "Test.WaiterTest",
          methods: {
            showMessage: function() {console.log("WAITERTEST 2: LISTO")},
            getRandSeconds: function() {
              return 3000;
            }
          }
        },
        "Waiter3": {
          inherits: "Test.WaiterTest",
          methods: {
            showMessage: function() {console.log("WAITERTEST 3: LISTO")},
            getRandSeconds: function() {
              return 5000;
            }
          }
        }
      }
    })
  }
)

runTest("Vistas Nombradas","Usando data-viewName, es posible mapear widgets en el widget padre.<br>"+
  "El valor de esta propiedad es una parametrizable string.<br>"+
  "data-sivName es la forma de invocar el código de un widget hijo en el código del widget padre.<br>" +
  "No debe ser empleado en preInitialize porque el ese punto aún no existe la relación.",
  '<div data-sivWidget="Test.Map1" data-widgetCode="Test.Map1">a<div data-sivId="target"></div>b</div>'+
  '<div data-sivWidget="Test.Map2" data-widgetCode="Test.Map2">z<div data-sivId="target"></div>q</div>'+
  // '<div data-sivWidget="Test.MapTest" data-widgetParams="" data-widgetCode="Test.MapTest">'+
  '<div data-sivWidget="Test.MapTest" data-widgetCode="Test.MapTest">'+
  '   <div data-sivView="Test.Map1" data-viewName="map1"></div>'+
  '   <div data-sivLoop="*instances" data-contextIndex="current">'+
  '       <div data-sivView="Test.Map2" data-viewName="map2-[%@current-index%]"></div>'+
  '   </div>'+
  '</div>',
  '<div data-sivView="Test.MapTest"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "MapTest": {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.instances={};
              for (var k=0;k<2;k++) {
                this.instances["prueba-"+k]="hola";
              }

            },
            initialize: function (params) {
              this.waitComplete().then(function(){
                this.map1.showLabel(" SOY MAP 1");
                for (var k=0;k<2;k++) {
                  this["map2-prueba-"+k].showLabel("SOY MAP2, INSTANCIA "+k);
                }
              }.bind(this));
            },
          }
        },
        "Map1":{
          inherits:"Siviglia.UI.Expando.View",
          methods:{
            preInitialize: function() {},
            initialize: function() {},
            showLabel: function(label) {
              this.target.html(label)
            },
          }
        },
        "Map2":{
          inherits:"Test.Map1"
        }
      }
    })
  }
)
runTest("Promise Expando","El expando Promise, ejecuta sus contenidos cuando una promesa se resuelve",
  // '<div data-sivWidget="Test.PromiseTest" data-widgetParams="" data-widgetCode="Test.PromiseTest">'+
  '<div data-sivWidget="Test.PromiseTest" data-widgetCode="Test.PromiseTest">'+
  // '   <div data-sivPromise="*myPromise" data-contextIndex="current">'+
  '   <div data-sivPromise="*myPromise">'+
  '       Hola!'+
  '   </div>'+
  '</div>',
  '<div data-sivView="Test.PromiseTest"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "PromiseTest": {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.myPromise=SMCPromise();
              setTimeout(function(){
                this.myPromise.resolve()
              }.bind(this),3000)
            },
            initialize: function (params) {
            }
          }
        }
      }

    })
  }
)


runTest("Seleccion de paths","Forzado de mostrado de campos ocultos con JqxWidgets<br>"+
  "Se muestra un formulario con los diferentes tipos de container, y se busca forzar el mostrado de unos campos u otros, aunque estén ocultos.<br>",
  '<div data-sivWidget="Test.ShowFields" data-widgetCode="Test.ShowFields">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '         data-sivParams=\'{"key":"ROOT","parent":"*typedObj","form":"*self"}\'>' +
  '</div>'+
  '</div>',
  '<div data-sivView="Test.ShowFields"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "ShowFields": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                  "FIELDS": {
                    "ROOT": {
                      "LABEL": "ROOT",
                      "TYPE": "Dictionary",
                      "VALUETYPE": {
                        "LABEL":"INNERCONT",
                        "TYPE": "Container",
                        "FIELDS": {
                          "f3": {
                            "LABEL":"F3",
                            "TYPE":"String",
                          },
                          "f4": {
                            "LABEL": "F4",
                            "TYPE": "Array",
                            "ELEMENTS": {
                              "LABEL": "TypeSwitcher",
                              "TYPE": "TypeSwitcher",
                              "TYPE_FIELD": "TYPE",
                              "ALLOWED_TYPES": {
                                "TYPE_ONE": {
                                  "LABEL": "TYPEONE",
                                  "TYPE": "Container",
                                  "FIELDS": {
                                    "f1": {
                                      "LABEL": "Field 1-1",
                                      "TYPE": "String"
                                    },
                                    "f2": {
                                      "LABEL": "Field 2-1",
                                      "TYPE": "Integer"
                                    },
                                    "TYPE":{
                                      "LABEL":"Tipo",
                                      "TYPE":"String",
                                      "FIXED":"TYPE_ONE"
                                    }
                                  }
                                },
                                "TYPE_TWO": {
                                  "LABEL": "TYPETWO",
                                  "TYPE": "Container",
                                  "FIELDS": {
                                    "f1": {
                                      "LABEL": "Field 2-1",
                                      "TYPE": "String"
                                    },
                                    "f2": {
                                      "LABEL": "Field 2-2",
                                      "TYPE": "Integer"
                                    },
                                    "TYPE": {
                                      "LABEL":"Tipo",
                                      "TYPE":"String",
                                      "FIXED":"TYPE_TWO"
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              );
              this.typedObj.setValue({
                "ROOT": {
                  "dict1": {
                    f3: "f3d1",
                    f4: [
                      {"TYPE": "TYPE_ONE", f1: "f1-1", "f2": 1},
                      {"TYPE": "TYPE_TWO", f1: "f1-1-a", "f2": 2},
                    ]
                  },
                  "dict2": {
                    f3: "f3d2",
                    f4:[
                      {"TYPE": "TYPE_ONE", f1: "f1-2", "f2": 3},
                      {"TYPE": "TYPE_TWO", f1: "f1-2-a", "f2": 4},
                    ]
                  },
                  "dict3": {
                    f3: "f3d3",
                    f4:[
                      {"TYPE": "TYPE_ONE", f1: "f1-3", "f2": 5},
                      {"TYPE": "TYPE_TWO", f1: "f1-3-a", "f2": 6},
                    ]
                  },
                }
              });
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
              var paths=[
                "/ROOT/dict2/f3",
                "/ROOT/dict3/f4/0/f1",
                "/ROOT/dict1/f4/1/f2"
              ]
              var idx = 0;
              var m = this;
              /*m.showPath(paths[1]);
                                setTimeout(function(){
                                    m.showPath(paths[2]);
                                },3000)*/
              /*
                                setInterval(function(){
                                   m.showPath(paths[idx]);
                                   idx=(idx+1)%paths.length;
                                },3000);*/
            },
            show: function () {},
          }
        }
      }
    })
  }
)
runTest("Inputs Simples","Tercera prueba de JqxWidgets<br>"+
  "Se prueban 2 cadenas dependientes, con source remoto<br>",
  '<div data-sivWidget="Test.Input3" data-widgetCode="Test.Input3">'+
  '<div class="type">'+
  '<div class="label">String con source DATASOURCE (MODEL LIST)</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"modelSelector"}\'></div>'+
  '</div>'+
  '<div class="type">'+
  '<div class="label">String con source DATASOURCE enlazado (MODEL FIELDS)</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"fieldSelector"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input3"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input3": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  modelSelector: {
                    "LABEL":"ModelSelector",
                    "TYPE": "Model"
                  },

                  fieldSelector: {
                    "LABEL":"FieldSelector",
                    "TYPE": "String",
                    "SOURCE": {
                      "TYPE": "DataSource",
                      "LABEL": "NAME",
                      "VALUE": "FIELD",
                      "MODEL": "/model/reflection/Model",
                      "DATASOURCE": "FieldList",
                      "PARAMS": {
                        "model": "[%#modelSelector%]"
                      },
                    }
                  }
                }
              });

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)
runTest("Inputs Simples 4","Tipo de dato ModelField<br>"+
  "Se crea un BTO equivalente al anterior, usando el tipo ModelField<br>"+
  "",
  '<div data-sivWidget="Test.Input4" data-widgetParams="" data-widgetCode="Test.Input4">'+
  '<div class="type">'+
  '<div class="label">Campo Modelo</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"model"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input4"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input4": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  model:
                    {
                      "LABEL":"ModelSelector",
                      "TYPE": "/model/reflection/Types/types/BaseType"
                    }
                }
              });

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)
runTest("Input de Container","Test de input para el tipo Container<br>",
  '<div data-sivWidget="Test.Input4" data-widgetParams="" data-widgetCode="Test.Input4">'+
  '<div class="type">'+
  '<div class="label">Container:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleContainer"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input4"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input4": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer:
                    {
                      "LABEL":"SimpleContainer",
                      "TYPE": "Container",
                      "FIELDS": {
                        "Field1": {
                          "LABEL": "Field 1",
                          "TYPE": "String"
                        },
                        "Field2": {
                          "LABEL": "Field 2",
                          "TYPE": "Integer"
                        }
                      }
                    }
                }
              });
              this.typedObj.simpleContainer={"Field1":"AAA","Field2":555};

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)
runTest("Input de Container: Uso de INPUTPARAMS","Mismo test anterior, pero utilizando INPUTPARAMS para sobreescribir el widget utilizado para el input Container<br>"+
  "Un formulario puede parametrizar los inputs por defecto, o parametrizarlos, usando el campo INPUTPARAMS, con el path a los inputs que se quieren parametrizar",
  '<div data-sivWidget="Test.Input5" data-widgetParams="" data-widgetCode="Test.Input5">'+
  '<div class="type">'+
  '<div class="label">Container:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleContainer"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input5"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input5": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer:
                    {
                      "LABEL":"SimpleContainer",
                      "TYPE": "Container",
                      "FIELDS": {
                        "Field1": {
                          "LABEL": "Field 1",
                          "TYPE": "String"
                        },
                        "Field2": {
                          "LABEL": "Field 2",
                          "TYPE": "Integer"
                        }
                      }
                    }
                },
                "INPUTPARAMS":{
                  "/simpleContainer":{
                    "INPUT": "GridContainer",
                    "JQXPARAMS":{width:700,height:500}
                  }
                }
              });
              this.typedObj.simpleContainer={"Field1":"AAA","Field2":555};

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)

runTest("Input de Dictionary","Input de Dictionary por defecto.<br>",

  '<div data-sivWidget="Test.Input6" data-widgetParams="" data-widgetCode="Test.Input6">'+
  '<div class="type">'+
  '<div class="label">Dictionary:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleDictionary"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input6"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input6": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleDictionary:
                    {
                      LABEL:"SimpleDictionary",
                      "TYPE": "Dictionary",
                      "VALUETYPE": {
                        "TYPE": "Container",
                        "FIELDS": {
                          "Field1": {
                            "LABEL": "Field 1",
                            "TYPE": "String"
                          },
                          "Field2": {
                            "LABEL": "Field 2",
                            "TYPE": "Integer"
                          }
                        }
                      }
                    }
                }
              });
              this.typedObj.simpleDictionary=
                {
                  aa:{"Field1":"AAA","Field2":555},
                  bb:{"Field1":"ZZZ","Field2":666}
                };

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)

runTest("Input de Dictionary (Tipos simples)","Input de Dictionary por defecto.<br>",

  '<div data-sivWidget="Test.Input7" data-widgetParams="" data-widgetCode="Test.Input7">'+
  '<div class="type">'+
  '<div class="label">Dictionary:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleDictionary"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input7"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input7": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleDictionary:
                    {
                      LABEL:"SimpleDictionary",
                      "TYPE": "Dictionary",
                      "VALUETYPE": {
                        "TYPE": "String"
                      }
                    }
                }
              });
              this.typedObj.simpleDictionary=
                {
                  aa:"AAA",
                  bb:"ZZZ"
                };

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)
runTest("Input de Array (Tipos simples)","Input de Array por defecto, cuando los elementos son tipos simples.<br>",

  '<div data-sivWidget="Test.Input8" data-widgetParams="" data-widgetCode="Test.Input8">'+
  '<div class="type">'+
  '<div class="label">Array:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleArray"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input8"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input8": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleArray:{
                    "LABEL":"SimpleArray",
                    "TYPE":"Array",
                    "ELEMENTS":{
                      "LABEL": "Field 1",
                      "TYPE": "String"
                    }
                  }
                }
              });
              this.typedObj.simpleArray=
                [
                  "AAA", "ZZZ"
                ];

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)

runTest("Input de Array (Tipos compuestos)","Input de Array por defecto, cuando los elementos son tipos complejos.<br>",

  '<div data-sivWidget="Test.Input9" data-widgetParams="" data-widgetCode="Test.Input9">'+
  '<div class="type">'+
  '<div class="label">Array:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleArray"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input9"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input9": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleArray:{
                    "LABEL":"SimpleArray",
                    "TYPE":"Array",
                    "ELEMENTS":{
                      "TYPE": "Container",
                      "FIELDS": {
                        "Field1": {
                          "LABEL": "Field 1",
                          "TYPE": "String"
                        },
                        "Field2": {
                          "LABEL": "Field 2",
                          "TYPE": "Integer"
                        }
                      }
                    }
                  }
                }
              });
              this.typedObj.simpleArray=
                [
                  {"Field1":"AAA","Field2":25},{"Field1":"ZZZ","Field2":30}
                ];

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)

runTest("Input de TypeSwitcher Basado en campo Tipo","En este typeswitcher, el valor del campo viene determinado por la existencia de un campo de tipo (en este caso, TYPE)<br>",

  '<div data-sivWidget="Test.Input10" data-widgetParams="" data-widgetCode="Test.Input10">'+
  '<div class="type">'+
  '<div class="label">Type Switcher:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleTypeSwitcher"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input10"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input10": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleTypeSwitcher:
                    {
                      "LABEL":"TypeSwitcher",
                      "TYPE": "TypeSwitcher",
                      "TYPE_FIELD": "TYPE",
                      "ALLOWED_TYPES": {
                        "TYPE_ONE": {
                          "TYPE": "String"
                        },
                        "TYPE_TWO": {
                          "TYPE": "Container",
                          "FIELDS": {
                            "Field1": {
                              "LABEL": "Field 1",
                              "TYPE": "String"
                            },
                            "Field2": {
                              "LABEL": "Field 2",
                              "TYPE": "Integer"
                            }
                          }
                        }
                      }
                    }
                }
              });
              this.typedObj.simpleTypeSwitcher={"TYPE":"TYPE_TWO","Field1":"AAA","Field2":77};
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            }

          }
        }

      }

    })
  }
)

runTest("Formulario de edicion de modelo remoto (I)","En este ejemplo, la plantilla y clase del formulario se declara localmente, pero se inicializa el formulario indicando qué formulario, y qué campos indice hay que cargar del servidor.<br>",

  '<div data-sivWidget="Test.Edit1" data-widgetCode="Test.Edit1">\n' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"name","parent":"/*type","form":"/*form","controller":"/*self"}\'></div>\n'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"tag","parent":"/*type","form":"/*form","controller":"/*self"}\'></div>\n'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"id_site","parent":"/*type","form":"/*form","controller":"/*self"}\'></div>\n'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"isPrivate","parent":"/*type","form":"/*form","controller":"/*self"}\'></div>\n'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"path","parent":"/*type","form":"/*form","controller":"/*self"}\'></div>\n'+
  '<div><input type="button" data-sivEvent="click" data-sivCallback="submit" value="Guardar"></div>\n' +
  '\n' +
  '</div>\n',
  '<div data-sivView="Test.Edit1" data-sivParams=\'{"id_page":2}\'></div>',
  function(){
    Siviglia.Utils.buildClass({
      "context":"Test",
      "classes":{
        Edit1:{
          "inherits":"Siviglia.inputs.jqwidgets.Form",
          "methods":{
            preInitialize:function(params)
            {
              this.self=this;
              var p={
                "keys":params,
                "model":"/model/web/Page",
                "form":"Edit"
              }
              return this.Form$preInitialize(p);
            }
          }
        }
      }
    });
  }
)

runTest("Formulario de edicion de modelo remoto (II)","En este ejemplo, tanto formulario como los datos se cargan remotamente.Es por eso que no hay plantilla,ni clase, y el namespace del formulario es el que espera el servidor.<br>",

  '',
  '<div data-sivView="Siviglia.model.web.Page.forms.Edit" data-sivParams=\'{"id_page":2}\'></div>',
  function(){
  }
)

runTest("Test de inputs con estado","Se prueba un bto con containers con una especificacion de estado.<br>",

  '<div data-sivWidget="Test.Input11" data-widgetParams="" data-widgetCode="Test.Input11">'+
  '<div class="type">'+
  '<div class="label">Type Switcher:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"cont1"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Input11"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Input11": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject(
                {
                  "FIELDS": {
                    cont1:
                      {
                        "LABEL":"Container",
                        "TYPE":"Container",
                        "FIELDS":{
                          "one":{"TYPE":"String","LABEL":"one"},
                          "two":{"TYPE":"String","LABEL":"two"},
                          "three":{"TYPE":"String","LABEL":"three"},
                          "state":{"TYPE":"State","VALUES":["E1","E2","E3"],"DEFAULT":"E1","LABEL":"State"}
                        },
                        'STATES' : {
                          'STATES' : {
                            'E1' : {
                              'FIELDS' : {'EDITABLE' : ['one','two']}
                            },
                            'E2' : {
                              'ALLOW_FROM':["E1"],
                              'FIELDS' : {'EDITABLE' : ['two','three']}
                            },
                            'E3' : {
                              'ALLOW_FROM':["E2"],
                              'FINAL':true,
                              'FIELDS' : {'REQUIRED' : ['three']}}
                          },
                          'FIELD' : 'state'
                        }
                      },

                  }
                });
              this.typedObj.cont1={"TYPE":"TYPE_TWO","Field1":"AAA","Field2":77};
              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            },

          }
        }

      }

    })
  }
)

runTest("Container y valores por defecto.","Un container cuyos campos no son tocados, y solo tienen los valores por defecto de los campos, deberia seguir teniendo valor nulo.",
  '<div data-sivWidget="Test.DefCont" data-widgetParams="" data-widgetCode="Test.DefCont">'+
  '<div class="type">'+
  '<div class="label">Container:</div>'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleContainer"}\'></div>'+
  '</div>'+
  '<input type="button" data-sivEvent="click" data-sivCallback="doSubmit" ></input>'+
  '</div>',
  '<div data-sivView="Test.DefCont"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "DefCont": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer:
                    {
                      "LABEL":"SimpleContainer",
                      "TYPE": "Container",
                      "FIELDS": {
                        "Field1": {
                          "LABEL": "Container 1",
                          "TYPE":"Container",
                          "FIELDS": {
                            "f1":{
                              "LABEL":"f1",
                              "TYPE": "String",
                              "DEFAULT":"ssss"
                            },
                            "f2":{
                              "LABEL":"f2",
                              "TYPE": "String",
                            }
                          }

                        },
                        "Field2": {
                          "LABEL": "Container 2",
                          "TYPE":"Container",
                          "FIELDS": {
                            "f1":{
                              "LABEL":"f3",
                              "TYPE": "String",
                              "DEFAULT":"ssss"
                            },
                            "f2":{
                              "LABEL":"f4",
                              "TYPE": "String",
                            }
                          }
                        },
                        "Field3": {
                          "LABEL": "Container 3",
                          "TYPE":"Container",
                          "FIELDS": {
                            "f1":{
                              "LABEL":"f5",
                              "TYPE": "String",
                              "DEFAULT":"ssss"
                            },
                            "f2":{
                              "LABEL":"f6",
                              "TYPE": "String",
                            }
                          }
                        }
                      }
                    }
                },
                "INPUTPARAMS":{
                  "/simpleContainer":{
                    "INPUT": "ByFieldContainer",
                  }
                }
              });
              this.typedObj.setValue({simpleContainer:{Field1:{f1:"aaa"}}})

              return this.Form$preInitialize({bto:this.typedObj});
            },
            doSubmit:function()
            {
              this.typedObj.save();
              console.dir(this.typedObj.getPlainValue());
            }

          }
        }

      }

    })
  }
);

runTest("Listado derivado de BaseGrid","Test de Widget de Listado,directamente dereivado de BaseGrid con datasource remoto,filtros, y subwidgets",
  '<div data-sivWidget="Test.ListViewerForm" data-widgetCode="Test.ListViewerForm">\n' +
  '        <div class="input">\n' +
  '            <div class="label">Id</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"id_page"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">id_site</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"id_site"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">namespace</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"namespace"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">Tag</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"tag"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">name</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"name"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">date_add</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"date_add"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">date_modified</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"date_modified"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">id_type</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"id_type"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">isPrivate</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"isPrivate"}\'></div>\n' +
  '        </div>\n' +
  '        <div class="input">\n' +
  '            <div class="label">Path</div>\n' +
  '            <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"path"}\'></div>\n' +
  '        </div>\n' +
  '    </div>\n' +
  '\n' +
  '    <div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer">\n' +
  '    </div>\n' +
  '\n' +
  '    <div data-sivWidget="Test.ListButton" data-widgetCode="Test.ListButton">\n' +
  '        <div>\n' +
  '            <input style="width:120px;margin:0px" type="button" value="Show Id" data-sivEvent="click" data-sivCallback="onClicked">\n' +
  '        </div>\n' +
  '    </div>',
  ' <div data-sivView="Test.ListViewer" data-sivLayout="Siviglia.lists.jqwidgets.BaseGrid"></div>',
  function(){
    Siviglia.Utils.buildClass({
      "context":"Test",
      "classes":{
        ListViewer:{
          "inherits":"Siviglia.lists.jqwidgets.BaseGrid",
          "methods":{
            preInitialize:function(params)
            {
              this.BaseGrid$preInitialize({
                  "filters":"Test.ListViewerForm",
                  "ds": {
                    "model": "/model/web/Page",
                    "name": "FullList",
                    "settings":{
                      pageSize:20
                    }
                  },
                  "columns":{
                    "id":{"Type":"Field","Field":"id_page","Label":"id",gridOpts:{width:"80px"}},
                    "Id-name":{"Label":"Pstring","Type":"PString","str":'<a href="#" onclick="javascript:alert([%*id_page%]);">[%*name%]</a>',gridOpts:{width:'10%'}},
                    "Wid":{"Label":"Wid","Type":"Widget","Widget":"Test.ListButton",gridOpts:{width:'10%'}},
                    "name":{"Type":"Field","Field":"name","Label":"name",gridOpts:{width:'10%'}},
                    //"namespace":{"Type":"Field","Field":"namespace","Label":"Namespace",gridOpts:{width:'10%'}},
                    "tag":{"Type":"Field","Field":"tag","Label":"Tag",gridOpts:{width:'10%'}},
                    "id_site":{"Type":"Field","Field":"id_site","Label":"id_site",gridOpts:{width:'10%'}},
                    "date_add":{"Type":"Field","Field":"date_add","Label":"Add date",gridOpts:{width:"30px",height:"100px"}},
                    "date_modified":{"Type":"Field","Field":"date_modified","Label":"Last Modified",gridOpts:{width:"50px"}},
                    "id_type":{"Type":"Field","Field":"id_type","Label":"Type id"},
                    "isPrivate":{"Type":"Field","Field":"isPrivate","Label":"Is Private"},
                    "path":{"Type":"Field","Field":"path","Label":"Path",gridOpts:{width:"40px"}},
                    "title":{"Type":"Field","Field":"title","Label":"Title"}
                  },
                  "gridOpts":{
                    width:"100%",
                    //rowsheight:100
                  }
                }
              );
            }

          }
        },
        ListButton:{
          "inherits":"Siviglia.UI.Expando.View",
          "methods":{
            preInitialize:function(params)
            {
              this.data=params.row;

            },
            initialize:function(params)
            {

            },
            onClicked:function(node,params)
            {
              alert(this.data.id_page);
            }
          }
        },
        ListViewerForm:{
          "inherits":"Siviglia.lists.jqwidgets.BaseFilterForm",

        }
      }
    });
  }
)




runTest("Source exclusivo","Prueba de source exclusivo, donde los elementos de un source, no pueden repetirse en el valor asociado. <br>",
  '<div data-sivWidget="Test.ExSource" data-widgetParams="" data-widgetCode="Test.ExSource">'+

  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"exSource"}\'></div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.ExSource"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "ExSource": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  exSource:
                    {
                      "LABEL":"FieldSelector",
                      "TYPE": "Array",
                      "ELEMENTS":{
                        "TYPE": "Integer"
                      },
                      "SOURCE": {
                        "TYPE": "Array",
                        "UNIQUE":true,
                        "DATA": [
                          {"a": 1, "message": "Opcion 1"},
                          {"a": 2, "message": "Opcion 2"},
                          {"a": 3, "message": "Opcion 3"},
                          {"a": 4, "message": "Opcion 4"},
                          {"a": 5, "message": "Opcion 5"},
                          {"a": 6, "message": "Opcion 6"},
                          {"a": 7, "message": "Opcion 7"}
                        ],
                        "LABEL": "message",
                        "VALUE": "a"
                      }
                    }
                },
                "INPUTPARAMS":{
                  "/exSource":{
                    "INPUT": "SourcedArray",
                  }
                }
              });

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            }

          }
        }

      }

    })
  }
)

runTest("Test Aliases","Prueba de edicion de alises de modelos. <br>",
  '<div data-sivWidget="Test.Aliases" data-widgetParams="" data-widgetCode="Test.Aliases">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"ALIASES"}\'></div>'+
  '<input type="button" data-sivEvent="click" data-sivCallback="show" value="Log">'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.Aliases"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "Aliases": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  "FIELDS": {
                    "LABEL": "Campos",
                    "TYPE": "Dictionary",
                    "VALUETYPE": "/model/reflection/Types/types/BaseType",
                    "HELP": "Campos existentes en el modelo"
                  },
                  "ALIASES": {
                    "LABEL": "Aliases",
                    "TYPE": "Dictionary",
                    "VALUETYPE": {
                      "TYPE": "TypeSwitcher",
                      "LABEL": "Tipo de Alias",
                      "TYPE_FIELD": "TYPE",
                      "ALLOWED_TYPES": {
                        "InverseRelation": {
                          "LABEL": "Relacion inversa",
                          "TYPE": "Container",
                          "FIELDS": {
                            "TYPE": {
                              "LABEL": "Type",
                              "TYPE": "String",
                              "FIXED": "InverseRelation"
                            },
                            "MODEL": {
                              "LABEL": "Model",
                              "TYPE": "Model",
                              "REQUIRED": true
                            },
                            "FIELDS": {
                              "LABEL": "Campos",
                              "HELP": "Campos que definen esta relacion (local=>remoto)",
                              "TYPE": "Dictionary",
                              "SOURCE": {
                                "TYPE": "Path",
                                "PATH": "#../../../FIELDS/[[KEYS]]",
                                "LABEL": "LABEL",
                                "VALUE": "LABEL"
                              },
                              "VALUETYPE": {
                                "LABEL": "Campo remoto",
                                "TYPE": "String",
                                "SOURCE": {
                                  "TYPE": "DataSource",
                                  "MODEL": "\/model\/reflection\/Model",
                                  "DATASOURCE": "FieldList",
                                  "PARAMS": {
                                    "model": "[%#..\/MODEL%]"
                                  },
                                  "LABEL": "NAME",
                                  "VALUE": "NAME"
                                }
                              },
                              "REQUIRED": true
                            },
                            "MULTIPLICITY": {
                              "LABEL": "Multiplicidad",
                              "TYPE": "String",
                              "SOURCE": {
                                "TYPE": "Array",
                                "DATA": [{
                                  "Label": "1:N"
                                }, {
                                  "Label": "0-1:N"
                                }
                                ],
                                "LABEL": "Label",
                                "VALUE": "Label"
                              }
                            },
                            "HELP": {
                              "LABEL": "Ayuda",
                              "TYPE": "Text",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "CARDINALITY": {
                              "LABEL": "Cardinalidad",
                              "TYPE": "Integer",
                              "HELP": "Numero aproximado de elementos del modelo remoto que apuntan a 1 elemento del modelo actual."
                            },
                            "KEEP_KEY_ON_EMPTY": {
                              "LABEL": "Permitir valor vac\u00edo",
                              "TYPE": "Boolean",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "REQUIRED": {
                              "TYPE": "Boolean",
                              "DEFAULT": false,
                              "LABEL": "Requerido",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "DEFAULT": {
                              "TYPE": "String",
                              "LABEL": "Valor por defecto",
                              "KEEP_KEY_ON_EMPTY": false
                            }
                          }
                        },
                        "RelationMxN": {
                          "LABEL": "Relacion Multiple",
                          "HELP": "Una relaci\u00f3n m\u00faltiple requiere que exista un modelo intermedio, con  ROLE tipo MULTIPLE_RELATIONSHIP, que almacena los campos relacionados.",
                          "TYPE": "Container",
                          "FIELDS": {
                            "TYPE": {
                              "LABEL": "Type",
                              "TYPE": "String",
                              "FIXED": "RelationMxN"
                            },
                            "MODEL": {
                              "LABEL": "Modelo intermedio",
                              "TYPE": "Model",
                              "HELP": "Modelo que contiene la relaci\u00f3n",
                              "REQUIRED": true
                            },
                            "REMOTE_MODEL": {
                              "LABEL": "Modelo remoto",
                              "HELP": "Modelo que est\u00e1 en el otro extremo de la relaci\u00f3n, con el que se relaciona a traves del modelo intermedio",
                              "TYPE": "Model",
                              "REQUIRED": true
                            },
                            "FIELDS": {
                              "LABEL": "Campos",
                              "HELP": "Campos que definen esta relacion (local=>remoto)",
                              "TYPE": "Dictionary",
                              "SOURCE": {
                                "TYPE": "Path",
                                "PATH": "#..\/..\/..\/FIELDS\/[[KEYS]]",
                                "LABEL": "LABEL",
                                "VALUE": "LABEL"
                              },
                              "VALUETYPE": {
                                "LABEL": "Campo remoto (en la tabla intermedia)",
                                "TYPE": "String",
                                "SOURCE": {
                                  "TYPE": "DataSource",
                                  "MODEL": "\/model\/reflection\/Model",
                                  "DATASOURCE": "FieldList",
                                  "PARAMS": {
                                    "model": "[%#..\/MODEL%]"
                                  },
                                  "LABEL": "NAME",
                                  "VALUE": "NAME"
                                }
                              },
                              "REQUIRED": true
                            },
                            "RELATIONS_ARE_UNIQUE": {
                              "LABEL": "Relaciones unicas",
                              "TYPE": "Boolean",
                              "DEFAULT": false
                            },
                            "CARDINALITY": {
                              "LABEL": "Cardinalidad",
                              "TYPE": "Integer"
                            },
                            "KEEP_KEY_ON_EMPTY": {
                              "LABEL": "Permitir valor vac\u00edo",
                              "TYPE": "Boolean",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "REQUIRED": {
                              "TYPE": "Boolean",
                              "DEFAULT": false,
                              "LABEL": "Requerido",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "DEFAULT": {
                              "TYPE": "String",
                              "LABEL": "Valor por defecto",
                              "KEEP_KEY_ON_EMPTY": false
                            }
                          }
                        }
                      }
                    },
                    "HELP": "Aliases (relaciones inversas y multiples)"
                  },

                }
              });
              this.typedObj.FIELDS={
                "uno":{"TYPE":"Boolean"},
                "dos":{"TYPE":"String"}
              };

              return this.Form$preInitialize({bto:this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
              console.log(this.typedObj.getPlainValue());
            }

          }
        }

      }

    })
  }
)

runTest("Pintado de cursores","Prueba de diseño para ver como pintar cursores mediante diagramas de fuerza. <br>",
  '<div class="container">'+
  '<div data-sivWidget="Test.DataGridForm" data-widgetCode="Test.DataGridForm">'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"id"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"parent"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"type"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"status"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"start"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"end"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"rowsProcessed"}\'></div>'+
  '</div>'+
  '</div>'+

  '<div data-sivWidget="Test.CursorNodeView" data-widgetParams="" data-widgetCode="Test.CursorNodeView">'+
  '<div data-sivId="cursor-container" id="cursorContainer" data-sivValue="class|cursorState_[%*status_cursor%]">'+

  '<div>'+
  '<span data-sivValue="class|cursor [%*paquete_modelo%]-cursors [%*cursorNameShort%]"></span><span data-sivValue="/*cursorNameShort"></span>'+
  '<span data-sivValue=" [[%*rowsProcessed%]]"></span>'+
  '<span data-sivValue="class|iconStatusCursor_[%*status_cursor%]::title|[%*status_text%]"></span>'+
  '</div>'+

  '<div class="extra_info">'+
  '<div><span data-sivValue="/*id_cursor"></span></div>'+
  '<div><span data-sivValue="/*fecha_start"></span></div>'+
  '<div><span data-sivValue="/*fileName"></span></div>'+
  '<div><div data-sivIf="[%/*errored%] == true">'+
  '<div><span class="cursor error_message" data-sivValue="/*error_message"></span></div>'+
  '</div></div>'+
  '</div>'+
  '</div>'+

  '</div>'+
  '</div>'+

  // '<div data-sivWidget="Test.CursorGraph" data-widgetCode="Test.CursorGraph"> <svg data-sivId="svgNode" style="width: 100%; height: 100%"></svg> </div>'+
  '<div data-sivWidget="Test.CursorGraph" data-widgetCode="Test.CursorGraph"> <svg data-sivId="svgNode"></svg> </div>'+

  '<div data-sivWidget="Test.DataGrid" data-widgetCode="Test.DataGrid">'+
  '<div data-sivId="filterNode"></div>'+
  '<div data-sivId="grid"></div>'+
  '</div>',

  '<div data-sivWidget="Test.ViewController" data-widgetCode="Test.ViewController">' +
  '<div data-sivView="Test.DataGrid" data-sivParams="{}"></div>' +
  '<div data-sivId="graphNode" style="width: 100%; height:100%"></div>' +
  '</div>'+

  '<div data-sivView="Test.ViewController" data-sivParams="{}"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        "CursorNodeView":{
          inherits:"Siviglia.UI.Expando.View",
          destruct:function() {
            this.item["*status"].removeListeners(this);
          },
          methods: {
            preInitialize:function(params) {
              // Obtención de variables para mostrar el contenido del cursor en params.item
              if (params.item.cursorDefinition && params.item.cursorDefinition.fileName != undefined)
                this.fileName = params.item.cursorDefinition.fileName.split('\\').pop().split('/').pop();
              else
                this.fileName = "---";
              this.item = params.item;
              this.id_cursor = params.item.id;

              // Se especifica el tipo solo si el nombre es vacío.
              this.cursorNameShort = (params.item.name == null) ? params.item.type.split("\\").pop() : params.item.name;
              this.status_cursor = params.item.status;
              this.rowsProcessed = params.item.rowsProcessed;
              this.fecha_start = params.item.start;
              this.error_message = params.item.error == null ? '' : params.item.error;
              this.status_text="";
              Siviglia.Path.eventize(params.item, "status");
              params.item["*status"].addListener("CHANGE", this, "onChangeStatus");

              // Siviglia.Path.eventize(params.item, "error");
              // params.item["*error"].addListener("CHANGE", this, "onErrorCursor");

              this.errored = (this.error_message !== '');

              // Dependiendo de la ruta del path del cursor, montar un path asociado a ese cursor
              // para asi definir los iconos asociados a los paquetes-modelos-lib o default
              this.path_cursor = params.item.type.split('\\');
              this.path_first = this.path_cursor.shift();
              switch (this.path_first) {
                case "lib":
                  this.paquete_modelo = "default";
                  break;
                case "model":
                  this.paquete_modelo = this.path_cursor[1] + "_" + this.path_cursor[2]; // paquete_modelo. Ej: ads_dfp
                  break;
                default:
                  this.paquete_modelo = "default";
                  break;
              }

              // Cargamos los valores enum de la definition model\sys\objects\Cursor\Definition.php
              var cursorModelDefinition = Siviglia.Model.loader.getModelDefinition("/model/sys/Cursor");
              this.statusFieldLabel = cursorModelDefinition.FIELDS.status.VALUES;

            },
            onChangeStatus:function()
            {
              // Si el listener ha recibido un evento de cambio, entonces se modifica el texto del estado,
              // según el valor del item.status y actualizamos el nombre 'status_text'
              this.status_text = this.statusFieldLabel[this.item.status];

              // actualizamos el estado del cursor para mostrar el icono asociado al estado
              this.status_cursor = this.item.status;

              // actualizamos las rows procesadas
              this.rowsProcessed = this.item.rowsProcessed;
            },
            onErrorCursor:function()
            {
              // mensaje de error capturado
              this.error_message = this.item.error;
            },
            initialize:function(params){
              this.onChangeStatus();
            }
          }
        },
        "CursorGraph":{
          inherits:"Siviglia.visual.Force",
          destruct:function() {},
          methods:{
            initialize:function(params)
            {
              this.existingCursors={};
              this.cursorNodes=[];
              this.cursorLinks=[];
              this.Force$initialize(params);
              this.svg.append("svg:defs").selectAll("marker")
              .data(["end"])      // Different link/path types can be defined here
              .enter().append("svg:marker")
              .attr("id","end")// This section adds in the arrows
              .attr("viewBox", "0 -5 10 10")
              .attr("refX", 15)
              .attr("refY", 0.5)
              .attr("markerWidth", 13)
              .attr("markerHeight", 13)
              .attr("orient", "auto")
              .append("svg:path")
              .attr('fill', '#999')
              .attr("d", "M0,-5L10,0L0,5");
            },
            onData:function(cursorInfo)
            {
              var c=this.existingCursors[cursorInfo.id];
              var parent=cursorInfo.parent;
              var container=cursorInfo.container;
              var id=cursorInfo.id;

              if(typeof c!=="undefined" && c!==null)
              {
                // Se updatean los posibles links..Se supone que nunca se va a cambiar un link,
                // solo se añaden...Es por eso que no se busca un link antiguo y se quita..
                if(c.parent!==parent)
                  this.cursorLinks.push({source:parent,target:id,type:"parent"});
                if(c.container!==container)
                  this.cursorLinks.push({source:container,target:id,type:"container"});

                for(var k in cursorInfo)
                  c[k]=cursorInfo[k];
              }
              else {
                this.existingCursors[cursorInfo.id] = cursorInfo;
                this.cursorNodes.push(cursorInfo);
                if(parent!==null)
                  this.cursorLinks.push({source:parent,target:id,type:"parent"})
                if(container!==null)
                  this.cursorLinks.push({source:container,target:id,type:"container"})
              }

              this.update();

            },
            getNodesAndLinks:function()
            {
              return {nodes:this.cursorNodes,links:this.cursorLinks};
            },
            updateLinks:function(links)
            {
              this.Force$updateLinks(links);

              this.graphLinks.attr("marker-end", "url(#end)");

              //this.graphLinks.attr('marker-end', function(d,i){ return 'url(#end)' })
            }
          }
        },
        "ViewController":{
          inherits:"Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          destruct:function()
          {
            /*if(this.modelView)
                                this.modelView.destruct();
                            if(this.currentItemView)
                                this.currentItemView.destruct();*/
            if(this.wampService)
            {
              this.wampService.call("com.adtopy.removeBusListener",[this.__identifier]);
            }
            this.cursorGraph.destruct();

          },
          methods:{
            preInitialize:function(params)
            {
              this.modelView=null;
              this.currentItemView=null;
              this.editing=false;
              this.shown="hidden";
              this.selectedIcon="";
              this.selectedName="";
              this.selectedModel="";
              this.selectedSubModel="";
              this.selectedResourceType="";
              this.selectedClass="";
              this.selectedFile="";

              //this.SubApp$preInitialize(params);
            },
            initialize:function(params) {
              //this.SubApp$initialize(params);
              var stack = new Siviglia.Path.ContextStack();
              this.cursorGraph=null;
              var cursorGraph=new Test.CursorGraph(
                "Test.CursorGraph",
                {
                  parent:this,
                  svgWidth:600,
                  svgHeight:400,
                  nodeWidget:'Test.CursorNodeView',
                  nodeWidth:300,
                  nodeHeight:300,
                  allowMultipleSelection:false,
                  rowIdField:'id',
                  distanceLinks: 1 //parece no tener efecto visual
                },
                {},
                $("<div></div"),
                stack,
              );
              cursorGraph.__build().then(function(instance){
                this.cursorGraph=instance;
                this.graphNode.append(instance.rootNode);
              }.bind(this))


              this.addListener("ON_CURSOR_SELECTED",this,"onCursorChanged");

              // Simulacion de recepcion de datos en un periodo de tiempo
              /*var events=[
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":"2021-05-09 23:14:41"}
                                ];
                                var curEvent=0;
                                var cInt;
                                cInt=setInterval(function(){
                                    if(curEvent == events.length) {
                                        clearInterval(cInt);
                                        return;
                                    }
                                    this.onCursor(events[curEvent]);
                                    curEvent=curEvent+1;
                                }.bind(this),1000);*/

              // Se pone un listener sobre cualquier cambio en reflection
              this.wampService=Siviglia.Service.get("wampServer");
              if(this.wampService)
              {
                this.__identifier=Siviglia.Model.getAppId();
                this.wampService.call("com.adtopy.replaceBusListener",[
                  {channel:'General',path:'/model/sys/Cursor/*',roles:0xFFF,appId:this.__identifier,userId:top.Siviglia.config.user.TOKEN}]);

                this.wampService.subscribe('busevent',function(data){
                  var channel=data[0];
                  var params=data[1];
                  var appData=data[2];
                  if(appData.appId===this.__identifier)
                  {
                    this.onCursor(params.data);
                  }

                }.bind(this))
              }
            },
            onCursorChanged:function(eventName, cursorData) {
              console.log('Click event listened by controller')
              this.onCursor(cursorData)
            },
            onCursor:function(cursorInfo)
            {
              // console.dir(JSON.stringify(cursorInfo));
              if(this.cursorGraph)
                this.cursorGraph.onData(cursorInfo)
            },
            onItemSelected:function(evName,params)
            {
              this.showItemData(params.selection[0].d);

              // Se prepara el nombre del widget de edicion.
              // Si el nombre del recurso era "model", se carga Siviglia.Reflection.Model.
            },
            onSelectionEmpty:function()
            {
              if(this.currentItemView)
              {
                this.componentViewContainer.html("");
              }
              this.editing=false;
              this.shown="hidden";
              //this.modelView.unselect(this.lastItemSelected.d);

            },
            closeComponentView:function()
            {
              this.onSelectionEmpty();
            },
            onBackgroundClicked:function()
            {
              this.onSelectionEmpty();
            },
            showItemData:function(d)
            {
              this.shown="shown";

              var f=new Adtopy.reflection.ResourceMeta();
              var meta=f.getResourceMeta(d);
              this.selectedIcon=meta.icon;
              this.selectedName=typeof d.name==="undefined"?d.class:d.name;
              this.selectedModel=typeof d.model==="undefined"?"":d.model;
              this.selectedSubModel=typeof d.submodel==="undefined" || d.submodel===null?"":d.submodel;
              this.selectedResourceType=d.resource;
              this.selectedClass=typeof d.class==="undefined"?"":d.class;
              this.selectedFile=typeof d.file==="undefined"?"":d.file;

            },
          }
        },
        // datasource para el Grid de los cursores: model\sys\objects\Cursor\js\Siviglia\lists\FullList.js
        "DataGrid":{
          "inherits":"Siviglia.lists.jqwidgets.BaseGrid",
          "methods": {
            preInitialize:function(params) {
              this.BaseGrid$preInitialize({
                "filters":"Test.DataGridForm",
                "ds":{
                  "model":"/model/sys/Cursor",
                  "name":"FullList",
                  "settings":{
                    pageSize:20
                  }
                },
                "columns": {
                  "id":            {"Type": "Field", "Field":"id", "Label":"Id Cursor", "gridOpts":{"width":"10%"}},
                  "parent":        {"Type": "Field", "Field":"parent", "Label":"Parent", "gridOpts":{"width":"10%"}},
                  "Type":          {"Type": "Field", "Field":"type", "Label":"Type Cursor", "gridOpts":{"width":"20%"}},
                  "status":        {"Type": "Field", "Field":"status", "Label":"Status Cursor", "gridOpts":{"width":"10%"}},
                  "start":         {"Type": "Field", "Field":"start", "Label":"Start Cursor", "gridOpts":{"width":"15%"}},
                  "end":           {"Type": "Field", "Field":"end", "Label":"End Cursor", "gridOpts":{"width":"15%"}},
                  "rowsProcessed": {"Type": "Field", "Field":"rowsProcessed", "Label":"Filas Procesadas", "gridOpts":{"width":"20%"}},
                },
                "gridOpts": { width:"100%" }
              });
            },
            initialize: function (params) {
              this.addListener("ON_CURSOR_SELECTED",this,"onCellClicked");
              this.BaseGrid$initialize(params);
              this.grid.on("cellclick",function(eventData){
                console.log('Cell clicked')
                var cursor=eventData.args.row.bounddata;
                this.__parentView.fireEvent("ON_CURSOR_SELECTED", cursor);
              }.bind(this));
            },
          }
        },
        "DataGridForm": {
          "inherits":"Siviglia.lists.jqwidgets.BaseFilterForm",
          "methods": { }
        },
      },
    })
  }
)


runTest("Grid de Cursores","Test que muestra un Grid de los cursores de la base de datos. <br>",

  '<div data-sivWidget="Test.CursorTree_GridForm" data-widgetCode="Test.CursorTree_GridForm">'+
  '<div class="widListForm Siviglia_sys_Cursor_lists_Test_CursorTree_GridForm">'+
  '<div class="widListFormFieldSet">'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"id"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"parent"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"type"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"status"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"start"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"end"}\'></div>'+
  '</div>'+
  '<div class="widField">'+
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"rowsProcessed"}\'></div>'+
  '</div>'+
  '</div>'+
  '</div>'+
  '</div>'+

  '<div data-sivWidget="Test.CursorTree_Grid" data-widgetCode="Test.CursorTree_Grid"><div>'+

  '<div data-sivId="filterNode"></div>'+
  '<div data-sivId="grid"></div>'+
  '</div></div>'+
  '<div data-sivWidget="Test.CursorTree_Controller" data-widgetCode="Test.CursorTree_Controller">'+
  '<div data-sivView="Test.CursorTree_Grid" data-viewName="grid"></div>'+
  '<div data-sivId="cursorGraphNode"></div>'+

  '</div>'
  ,

  '<div data-sivView="Test.CursorTree_Controller"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        // datasource para el Grid de los cursores: model\sys\objects\Cursor\js\Siviglia\lists\FullList.js
        "CursorTree_Controller":{
          inherits:"Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          destruct:function(){
            this.reset();
          },
          methods:{
            preInitialize:function(params){
              this.currentGraph=null;
            },
            initialize:function(params){


              this.addListener("ON_CURSOR_SELECTED",this,"onCursorChanged");
            },
            reset:function()
            {
              if(this.currentGraph!==null)
                this.currentGraph.destruct();
              this.cursorGraphNode.html("");
            },
            onCursorChanged:function(evName,param)
            {
              var cursorId=param.cursor;
              alert("CAMBIADO A CURSOR:"+cursorId);

            }
          }
        },
        "CursorTree_Grid":{
          "inherits":"Siviglia.lists.jqwidgets.BaseGrid",
          "methods":
            {
              preInitialize:function(params)
              {
                this.BaseGrid$preInitialize({
                  "filters":"Test.CursorTree_GridForm",
                  "ds":{
                    "model":"/model/sys/Cursor",
                    "name":"FullList",
                    "settings":{
                      pageSize:20
                    }
                  },
                  "columns": {
                    "id":               {"Type": "Field", "Field":"id", "Label":"Id Cursor", "gridOpts":{"width":"10%"}},
                    "parent":           {"Type": "Field", "Field":"parent", "Label":"Parent", "gridOpts":{"width":"10%"}},
                    "Type":             {"Type": "Field", "Field":"type", "Label":"Type Cursor", "gridOpts":{"width":"20%"}},
                    "status":           {"Type": "Field", "Field":"status", "Label":"Status Cursor", "gridOpts":{"width":"10%"}},
                    "start":            {"Type": "Field", "Field":"start", "Label":"Start Cursor", "gridOpts":{"width":"15%"}},
                    "end":              {"Type": "Field", "Field":"end", "Label":"End Cursor", "gridOpts":{"width":"15%"}},
                    "rowsProcessed":    {"Type": "Field", "Field":"rowsProcessed", "Label":"Filas Procesadas", "gridOpts":{"width":"20%"}},
                  },
                  "gridOpts": { width:"100%" }
                });
              },
              initialize:function(params)
              {
                this.BaseGrid$initialize(params);
                this.grid.on("cellclick",function(args){
                  var cursorId=args.args.row.bounddata.id;
                  this.__parentView.fireEvent("ON_CURSOR_SELECTED",{cursor:cursorId});

                }.bind(this));
              }
            }
        },
        "CursorTree_GridForm":
          {
            "inherits":"Siviglia.lists.jqwidgets.BaseFilterForm",
            "methods": { }
          }

      },

    })
  }
)
runTest("Pruebas con BTO remoto","Tratamos de sacar la informaci'on de un BTO",
  '<div data-sivWidget="Test.BTOtest" data-widgetCode="Test.BTOtest">'+
  '<div data-sivLoop="*ds/data" data-contextIndex="server">'+
  '<div style="border:1px solid black;margin:2px">'+
  '<div data-sivValue="[%@server%]"></div>'+
  /*'<div d ata-sivLoop="[%@server%]" data-contextIndex="serverParam">'+
      '<div data-sivValue="[%@serverParam-index%] : [%@serverParam%]">'+*/
  '</div>'+
  '</div>'+
  '</div>'+
  '</div>'+
  '</div>'+
  '</div>',
  '<div data-sivView="Test.BTOtest" style="background-color:green"></div>' ,
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'BTOtest':{
          inherits: "Siviglia.UI.Expando.View",
          destruct:function() {
            this.ds.destruct();
          },
          methods:{
            preInitialize:function(params) {
              /*this.ds={}
                    this.ds.data=[
                    {key1: 'value1'},
                    {key2: 'value2'},
                    {key3: 'value3'},
                  ]*/
              this.ds=new Siviglia.Model.DataSource("/model/web/Site","FullList",{});
              this.ds.freeze();
              this.ds.settings.__start=0;
              this.ds.settings.__count=5;
              return this.ds.unfreeze();
            },
            initialize:function(params){}
          }
        }
      }

    })
  }
)

runTest("SivLoop","SivLoop atraviesa una variable iterable. En cada iteracion, establece una variable contextual (prefijo @), que apunta al elemento actual.",
  '<div data-sivWidget="Test.SivLoop" data-widgetCode="Test.SivLoop">' +
  '   <div data-sivLoop="/*simpleArray" data-contextIndex="current"><div>' +
  '<div data-sivLoop="@current" data-contextIndex="current2">'+
  '       <span data-sivValue="[%/@current2%]"></span></div></div></div></div>',
  '<div data-sivView="Test.SivLoop"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'SivLoop':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.simpleArray=[["cad1"],["cad2"],["cad3"]];
              top.sss=this.simpleArray;
            },
            initialize: function (params) {
              setTimeout(function(){
                this.simpleArray[0].push("adios");
              }.bind(this),1000);
              setTimeout(function(){
                this.simpleArray.push(["nuevo"]);
              }.bind(this),1500);
              setTimeout(function(){
                this.simpleArray[2].push("uno mas");
              }.bind(this),2000);
              setTimeout(function(){
                this.simpleArray.splice(1,2);
              }.bind(this),3000);

            }
          }
        }
      }

    })
  }
)
runTest("ParametrizableString",
  "Verificacion de las distintas funcionalidades de las ParametrizableString",
  '<div data-sivWidget="Test.ParametrizedStrings" data-widgetCode="Test.ParametrizedStrings">' +
  '<div data-sivValue="Sustitución simple: [%*okMessage%]"></div>' +
  '<div data-sivValue="Comprobación de existencia de una variable: [%*stringVar:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Negacion existencia de variable: [%!*noVar:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion <: [%/*intVar < 3:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion >: [%/*intVar > 1:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion == en integer: [%*intVar == 2:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion == en string: [%*stringVar == testString:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion == en array: [%*arrayVar == [1]:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion == en object (solo funciona por referencia): [%*objVar == *objVar:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion != en integer: [%*intVar != 1:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion != en string: [%*stringVar != noString:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion del tipo String: [%*stringVar is string:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion del tipo Integer: [%*intVar is int:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion del tipo Array: [%*arrayVar is array:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion del tipo Object: [%*intVar is plainObject:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Verificacion del tipo Function: [%*funcVar is function:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Concatenación de verificaciones: [%*stringVar is string,*intVar is int:{%*okMessage%}%]"></div>' +
  '<div data-sivValue="Transformacion-capitalize: [%*stringVar:{%*okMessage:ucfirst%}%]"></div>' +
  '<div data-sivValue="Transformacion-default: [%*stringVar:{%*noVar:default \'OK!\'%}%]"></div>' +
  '<div data-sivValue="Transformacion-repeat: [%*stringVar:{%*okMessage:str_repeat 2%}%]"></div>' +
  '<div data-sivValue="Concatenacion de transformaciones: [%*stringVar:{%*noVar:default \'ok\'|ucfirst|str_repeat 2%}%]"></div>' +
  '</div>',
  '<div data-sivView="Test.ParametrizedStrings"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'ParametrizedStrings':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.okMessage="OK!"
              this.stringVar='testString'
              this.intVar=2
              this.arrayVar=[1]
              this.objVar={a:'a'}
              this.funcVar=function(){}
              this.testArray=[]
            },
            initialize: function (params) {
            }
          }
        }
      }
    })
  }
)
runTest("ParametrizableString testing",
  "testeo rapido de PS",
  '<div data-sivWidget="Test.ParametrizedStrings2" data-widgetCode="Test.ParametrizedStrings2">' +
  '<div data-sivValue="Concatenacion de transformaciones: [%*stringVar:{%*noVar:default \'ok\'|ucfirst|str_repeat 2%}%]"></div>' +
  '</div>',
  '<div data-sivView="Test.ParametrizedStrings2"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'ParametrizedStrings2':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.okMessage="OK!"
              this.stringVar='testString'
              this.intVar=2
              this.arrayVar=[1]
              this.objVar={a:'a'}
              this.funcVar=function(){}
              this.testArray=[]
            },
            initialize: function (params) {
            }
          }
        }
      }
    })
  }
)
