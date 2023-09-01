(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "Container",
          "SET_ON_EMPTY": true,
          "FIELDS": {
            "f1": {
              "TYPE": "String",
              "MINLENGTH": 2
            },
            "f2": {
              "TYPE": "Integer"
            }
          }
        },
        "TIPO2": {
          "TYPE": "Container",
          "SET_ON_EMPTY": false,
          "FIELDS": {
            "f1": {
              "TYPE": "String",
              "MINLENGTH": 2,
              "KEEP_KEY_ON_EMPTY": true
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
  // NOTA: El path /TIPO2, tiene SET_ON_EMPTY a falso, pero a su vez tiene el campo f1 que tiene KEEP_ON_EMPTY a true.
  var r = t2.getPlainValue();
  // Lo que tiene que haber aqui es:
  // r.f1 : al tener SET_ON_EMPTY a true, pero sus campos son nulos, r.f1=={}
  // r.TIPO2 debe ser igual a {f1:null}
  var nKeys = 0;
  for (var k in r.f1)
    nKeys++;
  var status = nKeys == 0 && typeof r.TIPO2 !== "undefined" && r.TIPO2 != null && typeof r.TIPO2.TIPO7 == "undefined" && r.TIPO2.f1 == null;
  t2.destruct();
  return status;


//codeEnd
//callbackEnd
})