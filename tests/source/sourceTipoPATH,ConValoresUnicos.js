(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String"
          },
          "SOURCE": {
            "TYPE": "Path",
            "PATH": "#../f4/[[KEYS]]",
            "LABEL": "LABEL",
            "VALUE": "VALUE",
            "UNIQUE": true
          }
        },
        "f4": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "String"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  // Intentamos meter un valor en el array, cuando la fuente esta vacia..
  var thrown = false;
  try {
    t1.f3 = ["hola"];
  } catch (e) {
    thrown = true;
  }
  var status = thrown && t1["*f3"].__isErrored();
  // Se introduce ahora esa key en el diccionario:
  t1.f4 = {"hola": "val_hola"};
  // Y ahora, automaticamente, el campo f3 ya no deberia tener error:
  status = status && !t1["*f3"].__isErrored();

  t1.destruct();
  status = status && countListeners() === 0;
  return status;

//codeEnd
//callbackEnd
})