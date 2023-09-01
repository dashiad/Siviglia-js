(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f2": {
          "TYPE": "String"
        },
        "TIPO2": {
          "TYPE": "Container",
          "FIELDS": {
            "f1": {
              "TYPE": "String",
              "MINLENGTH": 2
            },
            "TIPO7": {
              "TYPE": "Container",
              "FIELDS": {
                "f1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f2": {
                  "TYPE": "Integer"
                }
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit

  var t2 = new Siviglia.model.BaseTypedObject(def);
  t2.TIPO2 = {"f1": "hola", "TIPO7": {f1: "Prueba", f2: 55}};
  var path = t2.TIPO2.TIPO7["*f2"].__getFieldPath();
  var status = path == "/TIPO2/TIPO7/f2";
  t2.destruct();
  return status;


//codeEnd
//callbackEnd
})