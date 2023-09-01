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
              "MINLENGTH": 2,
              "REQUIRED": true
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
  var e = 0;
  var result = false;
  t2.TIPO2 = {"TIPO7": {f1: "Prueba", f2: 55}};
  try {
    t2.save();
  } catch (q) {
    result = (q.type == "BaseTypedException" && q.code == Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD);
  }
  t2.TIPO2.f1 = "lala";
  t2.save();
  t2.destruct();
  return result;


//codeEnd
//callbackEnd
})