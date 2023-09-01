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
            "f2": {
              "TYPE": "Integer"
            }
          }
        }
      },
      "DEFAULT": {
        "f2": "hola",
        "TIPO2": {
          "f1": "adios",
          "f2": 50
        }
      }
    }
//definitionEnd

//codeInit
  var t2 = new Siviglia.model.BaseTypedObject(def);
  var status = t2.f2 == "hola" && t2.TIPO2.f2 == 50;
  t2.destruct();
  return status;

//codeEnd
//callbackEnd
})