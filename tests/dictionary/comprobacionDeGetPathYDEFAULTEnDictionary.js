(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "sf3": {
                "TYPE": "String",
                "MINLENGTH": 2
              },
              "sf4": {
                "TYPE": "Integer"
              }
            }
          },
          "DEFAULT": {
            "uno": {
              "sf3": "Hola",
              "sf4": 1
            },
            "dos": {
              "sf3": "Hola2",
              "sf4": 2
            },
            "tres": {
              "sf3": "Hola3",
              "sf4": 3
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);

  var path = t1.f3.uno["*sf3"].__getFieldPath();
  var status = path == "/f3/uno/sf3";
  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})