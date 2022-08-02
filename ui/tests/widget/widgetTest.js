document.getElementsByTagName('title')[0].innerHTML = 'Tests widget'

runTest("sivWidget: widget mínimo",
  "Un widget se compone de una <b>plantilla HTML</b> y una <b>clase asociada</b>. Estos widgets se invocan mediante una <b>vista</b>.<br>" +
  "El widget se declara mediante el atributo <b>sivWidget</b>.<br>"+
  "La clase se declara mediante el atributo <b>widgetCode</b>.<br>"+
  "La vista invoca un widget dándole al atributo <b>sivView</b> el valor de sivWidget del widget deseado.",
  '<div data-sivWidget="widget-name" data-widgetCode="ContextName.ClassName">' +
  '<span>Hello, world!</span>' +
  '</div>',
  '<div data-sivView="widget-name"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'ContextName',
      classes: {
        'ClassName': {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {},
            initialize: function (params) {},
          }
        }
      }
    })
  }
)
runTest("sivWidget: variables de la clase en el widget",
  "Para emplear una variable de la clase en el widget debe declararse con un valor no nulo en preInitialize.<br>" +
  "Para acceder en el widget al contexto de la clase asociada, y por tanto a sus variables de clase, se emplea el prefijo \"<b>*</b>\"",
  '<div data-sivWidget="class-var" data-widgetCode="Test.ClassVar">' +
  '<span data-sivValue="[%*message%]"></span>' +
  '</div>',
  '<div data-sivView="class-var"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'ClassVar':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.message="Hello, world again!"
            },
            initialize: function (params) {}
          }
        }
      }
    })
  }
)
runTest("sivWidget: ciclo de vida",
  "El ciclo de vida de un widget es: <u>llamada a preInitialize</u> -> <u>renderizado</u> -> <u>llamada a initialize</u>.<br>" +
  "En <b>preInitialize</b> aun no se ha renderizado la plantilla, siendo el punto en el que deben declararse las variables usadas en la plantilla.<br>" +
  "Después de preInitialize y antes de initialize se <b>renderiza la plantilla</b>, bindeando las variables del widget a las variables de la clase.<br>" +
  "Finalmente, se ejecuta <b>initialize</b>. En ese punto las variables ya están renderizadas por lo que si se cambian en esta fase, la plantilla se renderiza de nuevo automáticamente.<br>" +
  "En el ejemplo una variable cambia en initialize mediante setInterval.",
  '<div data-sivWidget="widget-life-cycle" data-widgetCode="Test.WidgetLifeCycle">' +
  '<span data-sivValue="[%*counter%]"></span>' +
  '</div>',
  '<div data-sivView="widget-life-cycle"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'WidgetLifeCycle':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.counter = 0;
            },
            initialize: function (params) {
              setInterval(function () {this.counter++}.bind(this), 2000);
            }
          }
        }
      }
    })
  }
)
runTest("sivWidget: acceso al valor de keys en objetos mediante path",
  "Las variables miembro de la clase se pueden navegar como si fueran paths",
  '<div data-sivWidget="path-access-to-var" data-widgetCode="Test.PathAccessToVar">' +
  '<span data-sivValue="[%*varName/0/key%]"></span>' +
  '</div>',
  '<div data-sivView="path-access-to-var"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'PathAccessToVar':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.varName=[
                { key:"Hello world!" }
              ]
            },
            initialize: function (params) {}
          }
        }
      }
    })
  }
)
runTest("sivWidget: paths dependientes",
  "Los paths pueden depender del valor al que apunten otros paths o del valor de variables.<br>" +
  "NOTA1: No puede emplearse variables obtenidas mediante composición empleando otras variables: \"[%*firstPartVar{%*secondPartVar%}%]\"<br>" +
  "NOTA2: Los paths pueden comenzar opcionalmente por el carácter \"/\" , pero los paths anidados (que usan {%...%}, en vez de [%...%]) no permiten ese caracter \"/\" extra.",
  '<div data-sivWidget="dependant-paths" data-widgetCode="Test.DependantPaths">' +
  '<span data-sivValue="[%/*obj/{%*obj/2/indexKey%}/key%]"></span><br></br>' +
  '<span data-sivValue="[%/*obj/{%*indexVar%}/key%]"></span>' +
  '</div>',
  '<div data-sivView="dependant-paths"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'DependantPaths':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.indexVar=0;
              this.obj=[
                {key:"Hello..."},
                {key:"...world!"},
                {indexKey: 0}
              ]
            },
            initialize: function (params) {
              setInterval(function(){this.obj[2].indexKey=(this.obj[2].indexKey+1)%2;}.bind(this),1000);
              setInterval(function(){this.indexVar=(this.indexVar+1)%2;}.bind(this),1000);
            }
          }
        }
      }
    })
  }
)
runTest("ParametrizableString (PS): Definición",
  "Se trata de una String en la que se pueden introducir variables que serán resueltas en tiempo de ejecución.<br>" +
  "Las PS pueden realizar las siguientes operaciones:<br>" +
  "Sustitución: sustituye el valor indicado entre \"[%\" y \"%]\"<br>" +
  "Sustitución anidada: sustituye primero el valor indicado entre \"{%\" y \"%}\" y posteriormente el resultado que haya entre \"[%\" y \"%]\"<br>" +
  "Verificación: se realiza la verificación indicada entre \"[%\" y \"{%\" y solo si el resultado es \"true\" se ejecuta la sustitución indicada entre \"{%\" y \"%}\"<br>" +
  "Transformación: Después de las posibles verificaciones y antes de la sustitución, se toma el valor final a renderizar y se modifica según lo indicado entre \"{%\" y \"%}\", separado del nombre de la variable mediante \":\"",
  '<div data-sivWidget="parametrizable-string" data-widgetCode="Test.ParametrizableString">' +
  '<div data-sivValue="Sustitución simple: [%*okMessage%]"></div>' +
  '<div data-sivValue="Sustitución anidada: [%*objVar/{%*keyName%}%]"></div>' +
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
  '<div data-sivView="parametrizable-string"></div>',
  function(){
    Siviglia.Utils.buildClass({
      context:'Test',
      classes:{
        'ParametrizableString':{
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.okMessage="test passed"
              this.stringVar='testString'
              this.intVar=2
              this.arrayVar=[1]
              this.objVar={a: 'test passed'}
              this.keyName = 'a'
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
runTest("Datasource","Tratamos de sacar la informaci'on de un BTO",
  '<div data-sivWidget="Test.BTOtest" data-widgetCode="Test.BTOtest">'+
  '<div data-sivLoop="*ds/data" data-contextIndex="server">' +
  '<div style="border:1px solid black">' +
  '<div data-sivValue="[%@server/host%]">' +
  // De momento no he conseguido iterar sobre un BTO
  // '<div data-sivLoop="@server" data-contextIndex="server-param">' +
  // '<div data-sivValue="[%@server-param-index%]: [%@server-param%]""></div>' +
  '</div>' +
  '</div>' +
  '</div>' +
  '</div>',
  '<div data-sivView="Test.BTOtest" style="background-color:green"></div>' ,
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        'BTOtest': {
          inherits: "Siviglia.UI.Expando.View",
          destruct: function () {
            this.ds.destruct();
          },
          methods: {
            preInitialize: function (params) {
              this.ds = new Siviglia.Model.DataSource("/model/web/Site", "FullList", {});
              this.ds.freeze();
              this.ds.settings.__start = 0;
              this.ds.settings.__count = 5;
              return this.ds.unfreeze();
            },
            initialize: function (params) {}
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
runTest("Comunicación entre widgets: eventos",
  "Se muestra la comunicación mediante eventos entre dos widget hijos mediante su padre.<br>",
  '<div data-sivWidget="child-widget-A" data-widgetCode="Test.ChildWidgetA">' +
  '   <div>' +
  '       <span style="background-color:#123180;color:white" data-sivEvent="click" data-sivCallback="onClick" data-sivParams=\'{"clickVal":"Evento lanzado"}\'>Soy hijo A .</span><span>y digo...</span>' +
  '       <span data-sivValue="[%*messageA%]"></span>' +
  '   </div>' +
  '</div>' +

  '<div data-sivWidget="child-widget-B" data-widgetCode="Test.ChildWidgetB">' +
  '   <div>' +
  '       <span>Soy hijo B y escucho...</span>' +
  '       <span data-sivValue="[%*messageB%]"></span>' +
  '   </div>' +
  '</div>' +

  '<div data-sivWidget="widget-comm" data-widgetCode="Test.WidgetComm">' +
  '   <div data-sivView="child-widget-A"></div>' +
  '   <div>' +
  '       <span>Soy padre y eschucho de hijo A...</span>' +
  '       <span data-sivValue="[%*listenedMessage%]"></span>' +
  '   </div>' +
  '   <div>' +
  '       <span>Soy padre y le digo a hijo B...</span>' +
  '       <span data-sivValue="[%*firedMessage%]"></span>' +
  '   </div>' +
  '   <div data-sivView="child-widget-B"></div>' +
  '</div>',
  '<div data-sivView="widget-comm"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        WidgetComm: {
          inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          methods: {
            preInitialize: function (params) {
              this.listenedMessage = 'nada de momento'
              this.firedMessage = 'nada de momento'
            },
            initialize: function (params) {
              this.__subViews[0].view.__view.addListener('EVENT_A', this, 'onEventListened')
            },
            onEventListened: function (event, params) {
              this.listenedMessage = params.eventVal
              console.log('evento escuchado en el parent: ', params.eventVal)
              this.firedMessage = this.listenedMessage
              this.fireEvent(event, this.firedMessage)
              console.log('evento lanzado por el parent: ', this.firedMessage)
            },
          }
        },
        ChildWidgetA: {
          inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          methods: {
            preInitialize: function (params) {
              this.messageA = "nada de momento"
              this.addListener('EVENT_A', this, 'onEvent')
            },
            onClick: function (event, params) {
              console.log('valor llegado con el click: ', params.clickVal)
              this.fireEvent('EVENT_A', {eventVal: params.clickVal})
            },
            onEvent: function (event, params) {
              this.messageA = params.eventVal
              console.log('evento escuchado en el mismo widget generador: ', params.eventVal)
            }
          }
        },
        ChildWidgetB: {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {
              this.messageB = "nada de momento"
              this.__parentView.addListener('EVENT_A', this, 'onEvent')
            },
            onEvent: function (event, params) {
              this.messageB = params
              console.log('evento escuchado en el widget hermano: ', params)
            },
          }
        },
      }
    })
  }
)
checkTests();