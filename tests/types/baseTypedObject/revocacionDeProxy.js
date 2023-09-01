(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "a1": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String"
          }
        },
        "a2": {
          "TYPE": "Array",
          "ELEMENTS": {
            "ELEMENTS": {
              "TYPE": "String"
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.a1 = ["a", "b", "c"];
  var s = t1.a1;
  var s1 = t1["*a1"].getPlainValue();
  t1.a1 = null;

  // Acceder a traves de s, da error
  var thrown = false;
  try {
    var q = t1.a1.length;
  } catch (e) {
    thrown = true;
  }
  // A traves de s1, si es posible acceder a la longitud
  var status = thrown && s1.length === 3;

  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})